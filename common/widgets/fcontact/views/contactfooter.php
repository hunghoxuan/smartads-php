<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 02/08/2017
 * Time: 15:32 CH
 */
use common\components\FHtml;

$height = (!empty($height) || !isset($height)) ? $height : "45px";
$main_color = isset($main_color) ? $main_color : FHtml::settingWebsiteMainColor();
$font_size = (!empty($font_size) || !isset($font_size)) ? $font_size : "22px";
$color = (!empty($color) || !isset($color)) ? $color : 'white';
$url = (!empty($url) || !isset($url)) ? $url : \common\components\FFrontend::createContactUrl();
$title = (!empty($title) || !isset($title)) ? $title : 'Hi ' . FHtml::settingCompanyName();
$margin = (!empty($margin) || !isset($margin)) ? $margin : 'auto';

$phone = FHtml::showPhone();
$whatsapp = FHtml::showWhatsapp('', $title);
$skype = FHtml::showSkype();
$email = FHtml::showEmail();

?>
<style>
    #fix-bottom {
        z-index: 999;
        /*text-align: center;*/
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: <?= $main_color ?>;
        padding:5px;

    }
    #fix-bottom a{
        margin: <?= $margin ?>;
        font-size: <?= $font_size ?>;
        color: <?= $color ?>;
    }
</style>

<a href="<?= $url ?>">
    <div id="fix-bottom" class="fixed-bottom">
        <div class="container" >
            <div class="row">
                <?= !empty($phone) ? "<div class='pull-left' style='margin-right:10px'>$phone</div>" : '' ?>
                <?= !empty($whatsapp) ? "<div class='pull-left' style='margin-right:10px'>$whatsapp</div>" : '' ?>
                <?= !empty($skype) ? "<div class='pull-left' style='margin-right:10px'>$skype</div>" : '' ?>
                <?= !empty($email) ? "<div class='pull-left' style='margin-right:10px'>$email</div>" : '' ?>
            </div>
        </div>
    </div>
</a>

