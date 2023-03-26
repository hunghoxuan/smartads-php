<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 13/07/2017
 * Time: 10:35 SA
 */
use common\components\FHtml;

$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingCompanyFacebook(false);
$email = isset($email) ? $email : \common\components\FHtml::settingCompanyEmail();
$background_css = isset($background_css) ? $background_css : '#a52a2a';
$color = isset($color) ? $color : '#ffffff' ;
$left = isset($left) ? $left : '50';
 ///#a52a2a , #ffffff
?>
<script>(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  crm____cs = {"s":"https://crm.nhanh.vn","u":"<?= $chat_url ?>","bc":"<?= $background_css ?>","c":"<?= $color ?>","t":"<?= FHtml::t('common','Chat with us') ?>","v":"crm-chat-is-close","d":"right","p":"<?= $left ?>"};
  js = d.createElement(s); js.id = id;
  js.src = crm____cs.s + '/api/plugin/sdk.js';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'crm-jssdk'));</script>