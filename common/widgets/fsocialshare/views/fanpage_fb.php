<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 12/07/2017
 * Time: 22:21 CH
 */
$name_page = isset($name_page) ? $name_page : 'GroupMoza' ;
//$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingCompanyFacebook(false);
$tabs = isset($tabs) ? $tabs : 'timeline';
$small_header = isset($small_header) ? $small_header : 'false';
$container_width = isset($small_header) ? $small_header : 'true';
$hide_cover = isset($hide_cover) ? $hide_cover : 'false';
$show_facepile = isset($show_facepile) ? $show_facepile : 'true';
$height = isset($height) ?$height : '500';
$width = isset($width) ? $width : '340';
$title = isset($title) ? $title : 'Moza';
?>
<div class="fb-page" data-height="<?= $height ?>" data-width="<?= $width ?>" data-href="https://www.facebook.com/<?= $name_page ?>" data-tabs="<?= $tabs ?>" data-small-header="<?= $small_header ?>" data-adapt-container-width="<?= $container_width ?>" data-hide-cover="<?= $hide_cover ?>" data-show-facepile="<?= $show_facepile ?>"><blockquote cite="https://www.facebook.com/<?= $name_page ?>/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/<?= $name_page ?>/"><?= $title ?></a></blockquote></div>
