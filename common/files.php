<?php
/* PROJECT INFO --------------------------------------------------------------------------------------------------------
   Version:   1.5.2
   Changelog: http://adaptive-images.com/changelog.txt

   Homepage:  http://adaptive-images.com
   GitHub:    https://github.com/MattWilcox/Adaptive-Images
   Twitter:   @responsiveimg

   LEGAL:
   Adaptive Images by Matt Wilcox is licensed under a Creative Commons Attribution 3.0 Unported License.

/* CONFIG ----------------------------------------------------------------------------------------------------------- */

/* Mobile detection
   NOTE: only used in the event a cookie isn't available. */
function is_mobile() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    return strpos($userAgent, 'mobile');
}

/* helper function: Send headers and returns an image. */
function sendImage($filename, $browser_cache) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (in_array($extension, array('png', 'gif', 'jpeg'))) {
        header("Content-Type: image/".$extension);
    } else {
        header("Content-Type: image/jpeg");
    }
    header("Cache-Control: private, max-age=".$browser_cache);
    header('Expires: '.gmdate('D, d M Y H:i:s', time()+$browser_cache).' GMT');
    header('Content-Length: '.filesize($filename));
    readfile($filename);
    exit();
}

/* helper function: Create and send an image with an error message. */
function sendErrorImage($message) {
    /* get all of the required data from the HTTP request */
    $document_root  = $_SERVER['DOCUMENT_ROOT'];
    $requested_uri  = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
    $requested_file = basename($requested_uri);
    $source_file    = $document_root.$requested_uri;

    if(!is_mobile()){
        $is_mobile = "FALSE";
    } else {
        $is_mobile = "TRUE";
    }

    $im            = ImageCreateTrueColor(800, 300);
    $text_color    = ImageColorAllocate($im, 233, 14, 91);
    $message_color = ImageColorAllocate($im, 91, 112, 233);

    ImageString($im, 5, 5, 5, "Adaptive Images encountered a problem:", $text_color);
    ImageString($im, 3, 5, 25, $message, $message_color);

    ImageString($im, 5, 5, 85, "Potentially useful information:", $text_color);
    ImageString($im, 3, 5, 105, "DOCUMENT ROOT IS: $document_root", $text_color);
    ImageString($im, 3, 5, 125, "REQUESTED URI WAS: $requested_uri", $text_color);
    ImageString($im, 3, 5, 145, "REQUESTED FILE WAS: $requested_file", $text_color);
    ImageString($im, 3, 5, 165, "SOURCE FILE IS: $source_file", $text_color);
    ImageString($im, 3, 5, 185, "DEVICE IS MOBILE? $is_mobile", $text_color);

    header("Cache-Control: no-store");
    header('Expires: '.gmdate('D, d M Y H:i:s', time()-1000).' GMT');
    header('Content-Type: image/jpeg');
    ImageJpeg($im);
    ImageDestroy($im);
    exit();
}

/* sharpen images function */
function findSharp($intOrig, $intFinal) {
    $intFinal = $intFinal * (750.0 / $intOrig);
    $intA     = 52;
    $intB     = -0.27810650887573124;
    $intC     = .00047337278106508946;
    $intRes   = $intA + $intB * $intFinal + $intC * $intFinal * $intFinal;
    return max(round($intRes), 0);
}

/* refreshes the cached image if it's outdated */
function refreshCache($source_file, $cache_file, $resolution) {
    if (file_exists($cache_file)) {
        // not modified
        if (filemtime($cache_file) >= filemtime($source_file)) {
            return $cache_file;
        }

        // modified, clear it
        unlink($cache_file);
    }
    return generateImage($source_file, $cache_file, $resolution);
}

/* generates the given cache file for the given source file with the given resolution */
function generateImage($source_file, $cache_file, $resolution) {
    global $sharpen, $jpg_quality;

    $extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));

    // Check the image dimensions
    $dimensions   = GetImageSize($source_file);
    $width        = $dimensions[0];
    $height       = $dimensions[1];

    // Do we need to downscale the image?
    if ($width <= $resolution) { // no, because the width of the source image is already less than the client width
        return $source_file;
    }

    // We need to resize the source image to the width of the resolution breakpoint we're working with
    $ratio      = $height/$width;
    $new_width  = $resolution;
    $new_height = ceil($new_width * $ratio);
    $dst        = ImageCreateTrueColor($new_width, $new_height); // re-sized image

    switch ($extension) {
        case 'png':
            $src = @ImageCreateFromPng($source_file); // original image
            break;
        case 'gif':
            $src = @ImageCreateFromGif($source_file); // original image
            break;
        default:
            $src = @ImageCreateFromJpeg($source_file); // original image
            ImageInterlace($dst, true); // Enable interlancing (progressive JPG, smaller size file)
            break;
    }

    if($extension=='png'){
        imagealphablending($dst, false);
        imagesavealpha($dst,true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $new_width, $new_height, $transparent);
    }

    ImageCopyResampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height); // do the resize in memory
    ImageDestroy($src);

    // sharpen the image?
    // NOTE: requires PHP compiled with the bundled version of GD (see http://php.net/manual/en/function.imageconvolution.php)
    if($sharpen == TRUE && function_exists('imageconvolution')) {
        $intSharpness = findSharp($width, $new_width);
        $arrMatrix = array(
            array(-1, -2, -1),
            array(-2, $intSharpness + 12, -2),
            array(-1, -2, -1)
        );
        imageconvolution($dst, $arrMatrix, $intSharpness, 0);
    }

    $cache_dir = dirname($cache_file);

    // does the directory exist already?
    if (!is_dir($cache_dir)) {
        if (!mkdir($cache_dir, 0755, true)) {
            // check again if it really doesn't exist to protect against race conditions
            if (!is_dir($cache_dir)) {
                // uh-oh, failed to make that directory
                ImageDestroy($dst);
                sendErrorImage("Failed to create cache directory: $cache_dir");
            }
        }
    }

    if (!is_writable($cache_dir)) {
        sendErrorImage("The cache directory is not writable: $cache_dir");
    }

    // save the new file in the appropriate path, and send a version to the browser
    switch ($extension) {
        case 'png':
            $gotSaved = ImagePng($dst, $cache_file);
            break;
        case 'gif':
            $gotSaved = ImageGif($dst, $cache_file);
            break;
        default:
            $gotSaved = ImageJpeg($dst, $cache_file, $jpg_quality);
            break;
    }
    ImageDestroy($dst);

    if (!$gotSaved && !file_exists($cache_file)) {
        sendErrorImage("Failed to create image: $cache_file");
    }

    return $cache_file;
}

function sendFile($file_path)
{
    return downloadFile($file_path, false);
}

function downloadFile($file_path, $is_attachment = true) {
//    // hide notices
//    @ini_set('error_reporting', E_ALL & ~ E_NOTICE);
//
////- turn off compression on the server
//    @apache_setenv('no-gzip', 1);
//    @ini_set('zlib.output_compression', 'Off');

    $path_parts = pathinfo($file_path);
    $file_name  = $path_parts['basename'];
    $file_ext   = $path_parts['extension'];

    // make sure the file exists
    if (is_file($file_path))
    {
        $file_size  = filesize($file_path);
        $file = @fopen($file_path,"rb");
        if ($file)
        {
            // set the headers, prevent caching
            header("Pragma: public");
            header("Expires: -1");
            header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
            header("Content-Disposition: attachment; filename=\"$file_name\"");

            // set appropriate headers for attachment or streamed file
            if ($is_attachment)
                header("Content-Disposition: attachment; filename=\"$file_name\"");
            else
                header('Content-Disposition: inline;');

            // set the mime type based on extension, add yours if needed.
            $ctype_default = "application/octet-stream";

            $content_types = array(
                "exe" => "application/octet-stream",
                "zip" => "application/zip",
                "mp3" => "audio/mpeg",
                "mp4" => "audio/mpeg",
                "mpg" => "video/mpeg",
                "avi" => "video/x-msvideo",
                'pdf' => 'application/pdf',
                'txt' => 'text/plain',
                'html' => 'text/html',
                'doc' => 'application/msword',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'jpeg' => 'image/jpg',
                'jpg' => 'image/jpg',
                'php' => 'text/plain',
                'swf' => 'application/x-shockwave-flash',
                'rar' => 'application/rar',
                'ra' => 'audio/x-pn-realaudio',
                'ram' => 'audio/x-pn-realaudio',
                'ogg' => 'audio/x-pn-realaudio',
                'wav' => 'video/x-msvideo',
                'wmv' => 'video/x-msvideo',
                'asf' => 'video/x-msvideo',
                'divx' => 'video/x-msvideo',
                'mpeg' => 'video/mpeg',
                'mpe' => 'video/mpeg',
                'mov' => 'video/quicktime',
                '3gp' => 'video/quicktime',
                'm4a' => 'video/quicktime',
                'aac' => 'video/quicktime',
                'm3u' => 'video/quicktime',
            );
            $ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
            header("Content-Type: " . $ctype);

            //check if http_range is sent by browser (or download manager)
            if(isset($_SERVER['HTTP_RANGE']))
            {
                list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if ($size_unit == 'bytes')
                {
                    //multiple ranges could be specified at the same time, but for simplicity only serve the first range
                    //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                    list($range, $extra_ranges) = explode(',', $range_orig, 2);
                }
                else
                {
                    $range = '';
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    exit;
                }
            }
            else
            {
                $range = '';
            }

            //figure out download piece from range (if set)
            list($seek_start, $seek_end) = explode('-', $range, 2);

            //set start and end based on range (if set), else set defaults
            //also check for invalid ranges.
            $seek_end   = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)),($file_size - 1));
            $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);

            //Only send partial content header if downloading a piece of the file (IE workaround)
            if ($seek_start > 0 || $seek_end < ($file_size - 1))
            {
                header('HTTP/1.1 206 Partial Content');
                header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size);
                header('Content-Length: '.($seek_end - $seek_start + 1));
            }
            else
                header("Content-Length: $file_size");

            header('Accept-Ranges: bytes');

            set_time_limit(0);
            fseek($file, $seek_start);

            while(!feof($file))
            {
                print(@fread($file, 1024*8));
                ob_flush();
                flush();
                if (connection_status()!=0)
                {
                    @fclose($file);
                    exit;
                }
            }

            // file save was a success
            @fclose($file);
            exit;
        }
        else
        {
            // file couldn't be opened
            header("HTTP/1.0 500 Internal Server Error");
            exit;
        }
    }
    else
    {
        // file does not exist
        header("HTTP/1.0 404 Not Found");
        exit;
    }
}

if (!function_exists('formatSizeUnits')) {
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('is_writable_recursive')) {

    function is_writable_recursive($dir)
    {
        if (is_dir($dir)) {
            if (is_writable($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (!is_writable_recursive($dir . "/" . $object)) return false;
                        else continue;
                    }
                }
                return true;
            } else {
                return false;
            }

        } else if (file_exists($dir)) {
            return (is_writable($dir));

        }
    }
}