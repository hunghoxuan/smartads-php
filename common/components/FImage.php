<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use creocoder\flysystem\FtpFilesystem;
use League\Flysystem\Filesystem;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use Globals;
use common\components\Setting;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use common\components\Config;
use yii\imagine\Image;


class FImage extends Image
{

    const IMAGE_TYPES = array
    (
        0=>'UNKNOWN',
        1=>'GIF',
        2=>'JPEG',
        3=>'PNG',
        4=>'SWF',
        5=>'PSD',
        6=>'BMP',
        7=>'TIFF_II',
        8=>'TIFF_MM',
        9=>'JPC',
        10=>'JP2',
        11=>'JPX',
        12=>'JB2',
        13=>'SWC',
        14=>'IFF',
        15=>'WBMP',
        16=>'XBM',
        17=>'ICO',
        18=>'COUNT'
    );

    const FONTS_SIZE = [
        'h1' => 30, 'h2' => 25, 'h3' => 20, 'h4' => 15, 'p' => 12, 'small' => 10
    ];

    public $background_file;
    public $background_color = '#fff';

    public $font_file;
    public $image_type = "png";
    public $width = 500;
    public $height = 500;
    public $margin_top = '20%';
    public $margin_left = '20%';
    public $current_y = 0;

    public $content;
    public $top = 0;
    public $font_size = 30;
    public $image;
    public $image_file;

    public static function createInstance() {
        $instance = new FImage();
        $instance->setFontPath(FConfig::getDefaultFontPath());

        return $instance;
    }

    public function getImageFile() {
        if (empty($this->image_file))
            $this->image_file = time();

        return $this->image_file;
    }

    public function setSize($width, $height) {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    public function setMargin($margin_top, $margin_left) {
        $this->margin_top = $margin_top;
        $this->margin_left = $margin_left;
        $this->current_y = $this->convertY($this->margin_top);
        return $this;
    }

    public function setRootPosition($x, $y) {
        $this->margin_top = $x;
        $this->margin_left = $y;
        $this->current_y = $this->convertY($this->margin_top);

        return $this;
    }

    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    public function setBackgroundColor($background) {
        $this->background_color = $background;
        return $this;
    }

    public function setBackgroundFile($background) {
        $this->background_file = $background;
        return $this;
    }

    public function getBackgroundFile() {
        return $this->background_file;
    }

    public function setFontPath($font_path) {
        $this->font_file = $font_path;
        return $this;
    }

    public function setFontSize($font_size) {
        $this->font_size = $font_size;
        return $this;
    }

    public function getFontSize($tag) {
        return isset(self::FONTS_SIZE[$tag]) ? self::FONTS_SIZE[$tag] : 20;
    }

    public function setImageType($extension) {
        $this->image_type = $extension;
        return $this;
    }

    public function getWidth() {
        return isset($this->image) ? imagesx($this->image) : 0;
    }
    public function getHeight() {

        return isset($this->image) ? imagesy($this->image) : 0;
    }

    public function resizeToHeight($height) {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resizeImage($width,$height);
        return $this;
    }

    public function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resizeImage($width,$height);
        return $this;
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resizeImage($width,$height);
        return $this;
    }

    public function resizeImage($width,$height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
        return $this;
    }

    public function render($image_type = '', $is_download = false) {
        if (empty($image_type))
            $image_type = $this->image_type;

        $extension = $image_type;

        if (!empty(FHtml::getRequestParam('download')))
            $is_download = true;

        if ($is_download) {
            $file = !empty($this->image_file) ? $this->image_file : (time() . ".$extension");
            header('Content-Disposition: Attachment; filename='. $file);
        }
        header("Content-type: image/$extension");

        $this->sendImageToBrowser();

        return $this;
    }

    public function save($filename, $image_type = '', $compression=75, $permissions=null) {
        $filename = FHtml::getFullFileName($filename);

        if (empty($image_type))
            $image_type = $this->image_type;

        if( $image_type == 'jpg' ) {
            imagejpeg($this->image,$filename,$compression);
        } elseif( $image_type == 'gif' ) {
            imagegif($this->image,$filename);
        } elseif( $image_type == 'png' ) {

            imagepng($this->image,$filename);
        } else {

            imagepng($this->image,$filename);
        }
        
        if( $permissions != null) {

            chmod($filename,$permissions);
        }
        

        return $this;
    }

    public function download() {
        return $this->render(true);
    }

    public function convertX($x) {
        if (is_string($x) && StringHelper::endsWith($x, '%')) {
            $x = str_replace("%", '', $x);
            $x = round(($this->width * $x)/100);
        }
        return $x;
    }

    public function convertY($y) {
        if (is_string($y) && StringHelper::endsWith($y, '%')) {
            $y = str_replace("%", '', $y);
            $y = round(($this->height * $y)/100);
        }
        return $y;
    }

    public function h1($text, $color = '#000', $font = '') {
        $x = $this->convertX($this->margin_left);
        $y = $this->convertX($this->current_y);
        $this->current_y = $y + $this->getFontSize('h1') * 1.5;
        $this->addContent($text, $x, $y, 'h1', $color, $font);
        return $this;
    }

    public function h2($text, $color = '#000', $font = '') {
        $x = $this->convertX($this->margin_left);
        $y = $this->convertX($this->current_y);
        $this->current_y = $y + $this->getFontSize('h2') * 1.5;
        $this->addContent($text, $x, $y, 'h2', $color, $font);
        return $this;
    }

    public function h3($text, $color = '#000', $font = '') {
        $x = $this->convertX($this->margin_left);
        $y = $this->convertX($this->current_y);
        $this->current_y = $y + $this->getFontSize('h3') * 1.5;
        $this->addContent($text, $x, $y, 'h3', $color, $font);
        return $this;
    }

    public function h4($text, $color = '#000', $font = '') {
        $x = $this->convertX($this->margin_left);
        $y = $this->convertX($this->current_y);
        $this->current_y = $y + $this->getFontSize('h4') * 1.5;
        $this->addContent($text, $x, $y, 'h4', $color, $font);
        return $this;
    }

    public function p($text, $color = '#000', $font = '') {
        $x = $this->convertX($this->margin_left);
        $y = $this->convertX($this->current_y);
        $this->current_y = $y + $this->getFontSize('p') * 1.5;
        $this->addContent($text, $x, $y, 'p', $color, $font);
        return $this;
    }

    public function addContent($text, $x = 0, $y = 0, $font_size = 25, $color = "#fff", $font = '') {
        if (empty($font))
            $font = $this->font_file;

        if (is_string($color))
            $color = static::hex2rgbArray($color);
        else if (is_array($color))
            $color = $color;
        else
            $color = [0, 0, 0];

        $x = $this->convertX($x);

        $y = $this->convertY($y);

        if (is_string($font_size)) {
            $font_size = $this->getFontSize(strtolower($font_size));
        }

        $image = $this->getImageResource();
        $image_color = imagecolorallocate($image, $color[0], $color[1], $color[2]);

        // Print Text On Image
        imagettftext($image, $font_size, 0, $x, $y, $image_color, $font, $text);

        return $this;
    }

    //http://consistentcoder.com/use-imagecopymerge()-to-watermark-an-image-with-php
    public function addImage($sourceImage, $src_xPosition = 0, $src_yPosition = 0, $srcTransparency = 50, $src_cropXposition = 0, $src_cropYposition = 0) {
        //set the source image (foreground)
        $sourceImage = FHtml::getFullFileName($sourceImage);

        //set the transparency of the source image
        //$srcTransparency = 50; //the higher the clearer, max is 100

        //get the size of the source image, needed for imagecopymerge()
        $arr  = static::getImageSize($sourceImage);
        $srcWidth = $arr['width']; $srcHeight = $arr['height'];

        //create a new image from the source image
        $src = $this->createImageResource($sourceImage);

        //create a new image from the destination image
        $dest = $this->getImageResource();

//        //set the x and y positions of the source image on top of the destination image
        $src_xPosition = $this->convertX($src_xPosition); //75 pixels from the left
        $src_yPosition = $this->convertY($src_yPosition); //50 pixels from the top
//
//        //set the x and y positions of the source image to be copied to the destination image
//        $src_cropXposition = 0; //do not crop at the side
//        $src_cropYposition = 0; //do not crop on the top

        /*
         * get the index of the color of a pixel of the source image (imagecolorat),
         * and define a color as transparent (imagecolortransparent)
         */
        imagecolortransparent($src, imagecolorat($src,0,0));

        //merge the source and destination images
        imagecopymerge($dest,$src,$src_xPosition,$src_yPosition,$src_cropXposition,$src_cropYposition,$srcWidth,$srcHeight,$srcTransparency);

//        //output the merged images to a file
//        imagejpeg($dest, $this->getImageFile(), 100);

        //destroy the source image
        imagedestroy($src);

        return $this;
    }

    public function addWaterMark($sourceImage = '', $src_xPosition = '0', $src_yPosition = '0', $srcTransparency = 5) {
        if (empty($sourceImage))
            $sourceImage = FHtml::getFullFileName(FHtml::getCurrentLogoUrl());

        return $this->addImage($sourceImage, $src_xPosition, $src_yPosition, $srcTransparency);

    }

    public function getImageResource() {
        if (isset($this->image))
            return $this->image;

        $this->image = $this->createImageResource($this->getBackgroundFile(), $this->image_type);
        if (isset($this->image)) {
            $this->width = imagesx($this->image);
            $this->height = imagesy($this->image);
        }
        return $this->image;
    }

    public function createImageResource($background = '', $image_type = 'png') {
        if (empty($image_type))
            $image_type = $this->image_type;

        $image_type = strtolower($image_type);
        $image = null;
        if (!empty($background)) {
            if ($image_type == 'png') {
                $image = imagecreatefrompng($background);
            } else if ($image_type == 'jpg') {
                $image = imagecreatefromjpeg($background);
            } else if ($image_type == 'png') {
                $image = imagecreatefromgif($background);
            }
        } else {
            $image = @imagecreate($this->width, $this->height);
            $background_color = static::hex2rgbArray($this->background_color);
            imagecolorallocate($image, $background_color[0], $background_color[1], $background_color[2]);
        }

        return $image;
    }

    public function sendImageToBrowser($image = null, $image_type = 'png') {
        if (empty($image_type))
            $image_type = $this->image_type;
        $image_type = strtolower($image_type);

        if (!isset($image))
            $image = $this->getImageResource();

        if ($image_type == 'png') {
            imagepng($image);
        } else if ($image_type == 'jpg') {
            imagejpeg($image);
        } else if ($image_type == 'png') {
            imagegif($image);
        }

        // Clear Memory
        imagedestroy($image);

        return $this;
    }

    // Convert color code to rgb
    public static function hex2rgb($hex) {
        return hex2rgbArray($hex, false);
    }

    public static function hex2rgbArray($hex, $is_array = true) {
        if (is_array($hex))
            return $is_array ? $hex : implode(",", $hex);

        $hex = str_replace("#", "", $hex);

        switch(strlen($hex)){
            case 1:
                $hex = $hex.$hex;
            case 2:
                $r = hexdec($hex);
                $g = hexdec($hex);
                $b = hexdec($hex);
                break;
            case 3:
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
                break;
            default:
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
                break;
        }

        $rgb = array($r, $g, $b);
        return $is_array ? $rgb : implode(",", $rgb);
    }

    public static function getImageSize($file, $fast_search = true) {
        if (is_object($file)) { //if file is already image
            $width = imagesx($file);
            $height = imagesx($file);
            $arr[0] = $width;
            $arr[1] = $height;
            $arr['width'] = $width;
            $arr['height'] = $height;

            return $arr;
        }

        $file = strtolower($file);
        $file = FFile::getFullFileName($file);

        $arr = null;
        if ($fast_search && !is_file($file)) {
            if (StringHelper::endsWith($file, '.jpg')) {
                $arr = static::getjpegsize($file);
            }
            else if (StringHelper::endsWith($file, '.png')) {
                $arr = static::getpngsize($file);
            }
        }

        if (!isset($arr)) {
            $arr = getimagesize($file);

            if (is_array($arr)) {
                $arr['width'] = $arr[0];
                $arr['height'] = $arr[1];
                $arr['type'] = isset(self::IMAGE_TYPES[$arr[2]]) ? self::IMAGE_TYPES[$arr[2]] : '';
            }
        }

        return $arr;
    }

    // Retrieve JPEG width and height without downloading/reading entire image.
    public static function getjpegsize($img_loc) {
        $handle = fopen($img_loc, "rb") or die("Invalid file stream.");
        $new_block = NULL;
        if(!feof($handle)) {
            $new_block = fread($handle, 32);
            $i = 0;
            if($new_block[$i]=="\xFF" && $new_block[$i+1]=="\xD8" && $new_block[$i+2]=="\xFF" && $new_block[$i+3]=="\xE0") {
                $i += 4;
                if($new_block[$i+2]=="\x4A" && $new_block[$i+3]=="\x46" && $new_block[$i+4]=="\x49" && $new_block[$i+5]=="\x46" && $new_block[$i+6]=="\x00") {
                    // Read block size and skip ahead to begin cycling through blocks in search of SOF marker
                    $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                    $block_size = hexdec($block_size[1]);
                    while(!feof($handle)) {
                        $i += $block_size;
                        $new_block .= fread($handle, $block_size);
                        if($new_block[$i]=="\xFF") {
                            // New block detected, check for SOF marker
                            $sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
                            if(in_array($new_block[$i+1], $sof_marker)) {
                                // SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
                                $size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
                                $unpacked = unpack("H*", $size_data);
                                $unpacked = $unpacked[1];
                                $height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
                                $width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
                                return array($width, $height);
                            } else {
                                // Skip block marker and read block size
                                $i += 2;
                                $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                                $block_size = hexdec($block_size[1]);
                            }
                        } else {
                            return FALSE;
                        }
                    }
                }
            }
        }
        return FALSE;
    }

    // Retrieve PNG width and height without downloading/reading entire image.
    public static function getpngsize( $img_loc ) {
        $handle = fopen( $img_loc, "rb" ) or die( "Invalid file stream." );

        if ( ! feof( $handle ) ) {
            $new_block = fread( $handle, 24 );
            if ( $new_block[0] == "\x89" &&
                $new_block[1] == "\x50" &&
                $new_block[2] == "\x4E" &&
                $new_block[3] == "\x47" &&
                $new_block[4] == "\x0D" &&
                $new_block[5] == "\x0A" &&
                $new_block[6] == "\x1A" &&
                $new_block[7] == "\x0A" ) {
                if ( $new_block[12] . $new_block[13] . $new_block[14] . $new_block[15] === "\x49\x48\x44\x52" ) {
                    $width  = unpack( 'H*', $new_block[16] . $new_block[17] . $new_block[18] . $new_block[19] );
                    $width  = hexdec( $width[1] );
                    $height = unpack( 'H*', $new_block[20] . $new_block[21] . $new_block[22] . $new_block[23] );
                    $height  = hexdec( $height[1] );

                    return array( $width, $height );
                }
            }
        }

        return false;
    }

    public function getImageErrors( $filename, $type = "", $minWidth = 0, $minHeight = 0, $maxWidth = 0, $maxHeight = 0, $maxFileSize = 0 )
    {
        $errors = array();
        if ( file_exists( $filename ) )
        {
            $ending = substr( $filename, strpos( $filename, "." ) );
            if ( is_array( $type ) )
            {
                $isTypeOf = false;
                foreach( $type as $eachtype )
                {
                    if ( $ending == $eachtype )
                    {
                        $isTypeOf = true;
                    }
                }
                if ( ! $isTypeOf )
                {
                    $errors[ 'type' ] = $ending;
                }
            }
            elseif ( $type != "" )
            {
                if ( $ending != $type )
                {
                    $errors[ 'type' ] = $ending;
                }
            }
            $size = getimagesize( $filename );
            if ( $size[ 0 ] < $minWidth )
            {
                $errors[ 'minWidth' ] = $size[ 0 ];
            }
            if ( $size[ 1 ] < $minHeight )
            {
                $errors[ 'minHeight' ] = $size[ 1 ];
            }
            if ( ( $maxWidth > $minWidth ) && ( $size[ 0 ] > $maxWidth ) )
            {
                $errors[ 'maxWidth' ] = $size[ 0 ];
            }
            if ( ( $maxHeight > $minHeight ) && ( $size[ 1 ] > $maxHeight ) )
            {
                $errors[ 'maxHeight' ] = $size[ 1 ];
            }
            if ( ( $maxFileSize > 0 ) && ( filesize( $filename ) > $maxFileSize ) )
            {
                $errors[ 'maxFileSize' ] = filesize( $filename );
            }
        }
        else
        {
            $errors[ 'filename' ] = "not existing";
        }
        return ( count( $errors ) > 0 ? $errors : null );
    }

    public static function convertPng2jpg($originalFile, $outputFile = '', $quality = 100) {
        $originalFile = FFile::getFullFileName($originalFile);
        $image = imagecreatefrompng($originalFile);
        if (empty($outputFile))
            $outputFile = str_replace('.png', '.jpg', $outputFile);

        imagejpeg($image, $outputFile.'.jpg', $quality);
        imagedestroy($image);
    }

    public static function resize_image($file, $w, $h, $crop=FALSE) {
        $file = FHtml::getFullFileName($file);
        $arr = static::getImageSize($file);
        $width = $arr['width']; $height = $arr['height'];

        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }
}