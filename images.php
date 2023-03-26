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

require_once 'php/files.php';
require_once 'config/global.php';

$resolutions   = array(1382, 992, 768, 480); // the resolution break-points to use (screen widths, in pixels)
$cache_path    = "assets/cache"; // where to store the generated re-sized images. Specify from your document root!
$jpg_quality   = 75; // the quality of any generated JPGs on a scale of 0 to 100
$sharpen       = true; // Shrinking images can blur details, perform a sharpen on re-scaled images?
$watch_cache   = true; // check that the adapted image isn't stale (ensures updated source images are re-cached)
$browser_cache = 60 * 60 * 24 * 7; // How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default)

/* END CONFIG ----------------------------------------------------------------------------------------------------------
------------------------ Don't edit anything after this line unless you know what you're doing -------------------------
--------------------------------------------------------------------------------------------------------------------- */

/* get all of the required data from the HTTP request */
$document_root = $_SERVER['DOCUMENT_ROOT'];
$root_folder   = $_SERVER['SCRIPT_FILENAME'];
$root_folder   = str_replace('/images.php', '/', $root_folder);
$cache_path    = $root_folder . $cache_path;

$requested_uri  = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
$requested_file = basename($requested_uri);
$source_file    = $document_root . $requested_uri;

$resolution = false;
$action     = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';

$image_auto_handle = defined('IMAGES_AUTO_HANDLE') ? IMAGES_AUTO_HANDLE : false;

// allow a file to be streamed instead of sent as an attachment
$is_attachment = isset($_REQUEST['stream']) ? false : true;

// check if the file exists at all
if (!file_exists($source_file)) {
	header("Status: 404 Not Found");
	exit();
}

$file_path  = $source_file;
$path_parts = pathinfo($file_path);
$file_name  = $path_parts['basename'];
$file_ext   = $path_parts['extension'];

// if force download
if ($action == 'download') {
	downloadFile($file_path);
	die;
}

//if not image then display
if ($image_auto_handle || !in_array($file_ext, ['jpg', 'png', 'gif'])) {
	sendFile($file_path);
	die;
}

//return the image directly, because cached image processing has error
sendImage($file_path, $browser_cache);

/* Does the UA string indicate this is a mobile? */
if (!is_mobile()) {
	$is_mobile = false;
} else {
	$is_mobile = true;
}

/* does the $cache_path directory exist already? */
if (!is_dir("$cache_path")) { // no
	if (!mkdir("$cache_path", 0755, true)) { // so make it
		if (!is_dir("$cache_path")) { // check again to protect against race conditions
			// uh-oh, failed to make that directory
			sendErrorImage("Failed to create cache directory at: $cache_path");
		}
	}
}

/* Check to see if a valid cookie exists */
if (isset($_COOKIE['resolution'])) {
	$cookie_value = $_COOKIE['resolution'];

	// does the cookie look valid? [whole number, comma, potential floating number]
	if (!preg_match("/^[0-9]+[,]*[0-9\.]+$/", "$cookie_value")) { // no it doesn't look valid
		setcookie("resolution", "$cookie_value", time() - 100); // delete the mangled cookie
	} else { // the cookie is valid, do stuff with it
		$cookie_data   = explode(",", $_COOKIE['resolution']);
		$client_width  = (int) $cookie_data[0]; // the base resolution (CSS pixels)
		$total_width   = $client_width;
		$pixel_density = 1; // set a default, used for non-retina style JS snippet
		if (@$cookie_data[1]) { // the device's pixel density factor (physical pixels per CSS pixel)
			$pixel_density = $cookie_data[1];
		}

		rsort($resolutions); // make sure the supplied break-points are in reverse size order
		$resolution = $resolutions[0]; // by default use the largest supported break-point

		// if pixel density is not 1, then we need to be smart about adapting and fitting into the defined breakpoints
		if ($pixel_density != 1) {
			$total_width = $client_width * $pixel_density; // required physical pixel width of the image

			// the required image width is bigger than any existing value in $resolutions
			if ($total_width > $resolutions[0]) {
				// firstly, fit the CSS size into a break point ignoring the multiplier
				foreach ($resolutions as $break_point) { // filter down
					if ($total_width <= $break_point) {
						$resolution = $break_point;
					}
				}
				// now apply the multiplier
				$resolution = $resolution * $pixel_density;
			} // the required image fits into the existing breakpoints in $resolutions
			else {
				foreach ($resolutions as $break_point) { // filter down
					if ($total_width <= $break_point) {
						$resolution = $break_point;
					}
				}
			}
		} else { // pixel density is 1, just fit it into one of the breakpoints
			foreach ($resolutions as $break_point) { // filter down
				if ($total_width <= $break_point) {
					$resolution = $break_point;
				}
			}
		}
	}
}

/* No resolution was found (no cookie or invalid cookie) */
if (!$resolution) {
	// We send the lowest resolution for mobile-first approach, and highest otherwise
	$resolution = $is_mobile ? min($resolutions) : max($resolutions);
}

/* if the requested URL starts with a slash, remove the slash */
if (substr($requested_uri, 0, 1) == "/") {
	$requested_uri = substr($requested_uri, 1);
}

/* whew might the cache file be? */
$cache_file = "$cache_path/$resolution/" . $requested_uri;

/* Use the resolution value as a path variable and check to see if an image of the same name exists at that path */
if (file_exists($cache_file)) { // it exists cached at that size
	if ($watch_cache) { // if cache watching is enabled, compare cache and source modified dates to ensure the cache isn't stale
		$cache_file = refreshCache($source_file, $cache_file, $resolution);
	}

	sendImage($cache_file, $browser_cache);
}

/* It exists as a source file, and it doesn't exist cached - lets make one: */
$file = generateImage($source_file, $cache_file, $resolution);
sendImage($file, $browser_cache);