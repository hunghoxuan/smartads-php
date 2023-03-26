<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 13/07/2017
 * Time: 10:04 SA
 */
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = isset($url) ? $url : $actual_link;
$layout = isset($layout) ? $layout : 'standard' ; //box_count, button_count, button
$action = isset($action) ? $action : 'like'; //recommend
$size = isset($size) ? $size : 'small'; //large
$faces = isset($faces) ? $faces : 'true'; //false
$share = isset($share) ?$share : 'true'; //false
?>
<div class="fb-like" data-href="<?= $url ?>" data-layout="<?= $layout ?>" data-action="<?= $action ?>" data-size="<?= $size ?>" data-show-faces="<?= $faces ?>" data-share="<?= $share ?>"></div>
