<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 12/07/2017
 * Time: 17:21 CH
 */
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = isset($url) ? $url : $actual_link;
$width = isset($width) ? $width : '';
$numposts =  isset($numposts) ? $numposts : 5;
?>

<div class="fb-comments" data-href="<?= $url ?>" data-width="<?= $width ?>" data-numposts="<?= $numposts ?>"></div>