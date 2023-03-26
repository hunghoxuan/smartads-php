<?php

use common\components\FHtml;
use yii\helpers\Url;
use yii\imagine\Image;


// Specify font path
$font_path = FHtml::getRootFolder() .  '/backend/web/themes/metronic/assets/fonts/verdana.ttf';

$imageObject = \common\components\FImage::createInstance();
$text = FHtml::getRequestParam('text', 'This is demo');
$size = \common\components\FImage::getImageSize(  '/backend/web/www/cardvisit.png');

$imageObject
    ->setSize(1000, 1000)
    ->setBackgroundFile(FHtml::getRootFolder() . "/backend/web/www/cardvisit.png")
        ->addContent(FHtml::settingCompanyName(), "10%", "5%", 'h3', '#000')
        ->addImage(FHtml::getCurrentLogoUrl(), '40%', '50%', 5)
    ->addWaterMark()
    //->resizeImage(500, 500)
//    ->writeContent($text, 20, 220, 'h2', '#000')
//    ->writeContent('fsdafas', 20, 260, 'small', '#000')
        ->setMargin('20%', '10%')->h1('H1: Sample text')->h2('H2: Sample text')->h3('H3: Sample text')->h4('H4: Sample text')->p('content abc deff')
        ->setMargin('50%', '20%')->h1('Title 1')->h1('Title 2')->h2('Title 3')->h2('Title 4')->p('content abc deff')

    ->save(FHtml::getRootFolder() . '/backend/web/www/cardvisit1.png')->render();

die;


// Text font size
$font_size = 10;

//Set the Content Type
header('Content-type: image/png');

// Create Image From Existing File
$jpg_image = imagecreatefrompng(FHtml::getRootFolder() . "/backend/web/www/cardvisit.png");

// Allocate A Color For The Text
$color = hex2rgbArray("#000");

$image_color = imagecolorallocate($jpg_image, $color[0], $color[1], $color[2]);

// Set Text to Be Printed On Image
$text = "This is a sunset!";

// Print Text On Image
imagettftext($jpg_image, $font_size, 0, 75, 300, $image_color, $font_path, $text);

// Send Image to Browser
imagejpeg($jpg_image);

// Clear Memory
imagedestroy($jpg_image);

die;

// Get settings from URL
$setting = isset($_GET['s']) ? $_GET['s'] : "FFF_000_350_350";
$setting = explode("_", $setting);


$img = array();

// Define image width, height, and color
switch($n = count($setting)) {
    case $n > 4 :
    case 3:
        $setting[3] = $setting[2];
    case 4:
        $img['width'] = (int) $setting[2];
        $img['height'] = (int) $setting[3];
    case 2:
        $img['background'] = $setting[0];
        $img['color'] = $setting[1];
        break;
    default:
        list($img['background'], $img['color'], $img['width'], $img['height']) = array('F', '0', 100, 100);
        break;
}
$background = explode(",",hex2rgb($img['background']));
$textColorRgb = explode(",",hex2rgb($img['color']));
$width = empty($img['width']) ? 100 : $img['width'];
$height = empty($img['height']) ? 100 : $img['height'];

// Get text from URL
$text = (string) isset($_GET['t']) ? urldecode($_GET['t']) : $width ." x ". $height;

// Create the image resource
$image = @imagecreate($width, $height) or die("Cannot Initialize new GD image stream");

// Create image background
$background_color = imagecolorallocate($image, $background[0], $background[1], $background[2]);

// Grab the width & height of the text box
$bounding_box_size = imagettfbbox($font_size, 0, $font_path, $text);
$text_width = $bounding_box_size[2] - $bounding_box_size[0];
$text_height = $bounding_box_size[7]-$bounding_box_size[1];

// Text x&y coordinates
$x = ceil(($width - $text_width) / 2);
$y = ceil(($height - $text_height) / 2);

// Define text color
$text_color = imagecolorallocate($image, $textColorRgb[0], $textColorRgb[1], $textColorRgb[2]);

// Write text to image
imagettftext($image, $font_size, 0, $x, $y, $text_color, $font_path, $text);

// Set the content type header - in this case image/png
header('Content-Type: image/png');

// Output the image
imagepng($image);

// Free up memory
imagedestroy($image);

die;

// Convert color code to rgb
function hex2rgb($hex) {
    return hex2rgbArray($hex, false);
}

function hex2rgbArray($hex, $is_array = true) {
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

?>
