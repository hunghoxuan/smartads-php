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

$phone = FHtml::showPhone(FHtml::settingCompanyPhone(), false);
$whatsapp = FHtml::showWhatsapp(FHtml::settingCompanyWhatsapp(), false, '', $title);
$skype = FHtml::showSkype(FHtml::settingCompanyChat(), false);
$email = FHtml::showEmail(FHtml::settingCompanyEmail(), false);
$facebook = FHtml::getFacebookChatLink(FHtml::settingCompanyFacebook());
$bottom = isset($bottom) ? $bottom : '30px';
$position =  isset($position) ? $position : 'right';
?>
<?php if ($position == 'left' || $position == 'right') { ?>
    <div class="li-left-sclblk">
        <ul id="footer-social" class="cnss-social-icon " style="text-align:left;">
            <li class="cn-fa-facebook cn-fa-icon " style=""><a class="cnss-facebook" target="_blank" href="<?= $facebook ?>" title="Facebook" style="width:55px;height:55px;padding:9px;margin:2px;color: #ffffff;border-radius: 50%;">
                    <i title="Facebook" style="font-size:37px;" class="fa fa-facebook"></i></a>
            </li>
            <li class="cn-fa-mail cn-fa-icon " style=""><a class="cnss-twitter" target="_blank" href="<?= $email ?>" title="Email" style="width:55px;height:55px;padding:9px;margin:2px;color: #ffffff;border-radius: 50%;">
                    <i title="Email" style="font-size:37px;" class="fa fa-twitter"></i></a>
            </li>
            <li class=" cn-fa-icon " style=""><a class="" target="_blank" href="<?= $phone ?>" title="Phone" style="width:55px;height:55px;padding:9px;margin:2px;color: #ffffff;border-radius: 50%;">
                    <i title="Phone" style="font-size:37px;" class=""><img width="55px" height="55px"  src="<?= \common\components\FHtml::getImageUrl('social/phone.png','www') ?>" alt="" title="" style="" class="animated"></i></a>
            </li>
            <li class=" cn-fa-icon " style=""><a class="" target="_blank" href="<?= $whatsapp ?>" title="Whatsapp" style="width:55px;height:55px;padding:9px;margin:2px;color: #ffffff;border-radius: 50%;">
                    <i title="Whatsapp" style="font-size:37px;" class=""><img width="55px" height="55px"  src="<?= \common\components\FHtml::getImageUrl('social/whatsapp.png','www') ?>" alt="" title="" style="" class="animated"></i></a>
            </li>
            <li class="cn-fa-skype cn-fa-icon " style=""><a class="cnss-skype" target="_blank" href="<?= $skype ?>" title="Skype" style="width:55px;height:55px;padding:9px;margin:2px;color: #ffffff;border-radius: 50%;">
                    <i title="Skype" style="font-size:37px;" class="fa fa-skype"></i></a>
            </li>
        </ul></div>
    <style>
        .li-left-sclblk {
            position: fixed;
            top: 20%;
            <?=$position ?>: 30px;
            z-index: 1002;
        }

        ul.cnss-social-icon {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .li-left-sclblk ul li {
             margin-bottom: 10px!important;
             list-style: none;
         }
        ul.cnss-social-icon li {
            font-size: 16px !important;
            font-weight: 400 !important;
            vertical-align: middle;
            float: none!important;
            width: auto!important;
            margin: 10px !important;
            list-style-type: none!important;
            border: none!important;
            padding: 0!important;
            background: none!important;
            line-height: normal!important;
        }
        ul.cnss-social-icon li.cn-fa-icon a.cnss-twitter {
            background-color: #1da1f2!important;
        }
        ul.cnss-social-icon li.cn-fa-icon a {
            display: block!important;
            text-align: center!important;
            -webkit-transition: width .5s!important;
            transition: all .5s!important;
            box-sizing: border-box!important;
            background-color: #999999!important;
        }
        .li-left-sclblk ul li a {
            width: 37px!important;
            height: 37px!important;
            font-size: inherit!important;
            margin: 0!important;
            line-height: 40px!important;
            padding: 0!important;
            box-shadow: 0 4px 5px rgba(0,0,0,.2) !important;
        }
        ul.cnss-social-icon li a, ul.cnss-social-icon li a img {
            /*box-shadow: none!important;
            -webkit-box-shadow: none;*/
        }
        ul.cnss-social-icon li a {
            border: none!important;
            text-decoration: none!important;
        }
        .fa {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .li-left-sclblk ul li a i {
            font-size: 20px!important;
        }
        ul.cnss-social-icon li.cn-fa-icon a.cnss-facebook {
            background-color: #3b5998!important;
        }
        ul.cnss-social-icon li.cn-fa-icon a.cnss-google-plus {
            background-color: #dc4a38!important;
        }
        ul.cnss-social-icon li.cn-fa-icon a.cnss-skype {
            background-color: #00aff0!important;
        }
    </style>

<?php } else { ?>
<div style="background-color: white; ;">
    <a href="<?= $facebook ?>" class="suntory-alo-phone suntory-alo-green" id="suntory-zalo-phoneIcon" style="right: 100px; bottom: 0<?= $bottom ?>;" target="_blank">
        <div class="suntory-alo-ph-circle-fill"></div>
        <div class="suntory-alo-ph-img-circle">
            <i class="img-fb" style="margin:-5px -19px;"><img width="55px" height="55px" src="<?= \common\components\FHtml::getImageUrl('social/facebook.png','www') ?>" alt="" title="" style="" class="animated"></i>
        </div>
    </a>
    <a href="<?= $skype ?>" class="suntory-alo-phone suntory-alo-green" id="suntory-zalo-phoneIcon" style="right: 160px; bottom: <?= $bottom ?>;" target="_blank">
        <div class="suntory-alo-ph-circle-fill"></div>
        <div class="suntory-alo-ph-img-circle">
            <i class="img-fb" style="margin: -5px -19px;"><img width="55px" height="55px" src="<?= \common\components\FHtml::getImageUrl('social/skype.png','www') ?>" alt="" title="" style="" class="animated"></i>
        </div>
    </a>

    <a href="<?= $phone ?>" class="suntory-alo-phone suntory-alo-green" id="suntory-zalo-phoneIcon" style="right: 220px; bottom: 0<?= $bottom ?>;" target="_blank">
        <div class="suntory-alo-ph-circle-fill"></div>
        <div class="suntory-alo-ph-img-circle">
            <i class="img-fb" style="margin:-5px -19px;"><img width="55px" height="55px" src="<?= \common\components\FHtml::getImageUrl('social/phone.png','www') ?>" alt="" title="" style="" class="animated"></i>
        </div>
    </a>

    <a href="<?= $whatsapp ?>" class="suntory-alo-phone suntory-alo-green" id="suntory-zalo-phoneIcon" style="right: 280px; bottom: 0<?= $bottom ?>;" target="_blank">
        <div class="suntory-alo-ph-circle-fill"></div>
        <div class="suntory-alo-ph-img-circle">
            <i class="img-fb" style="margin:-5px -19px;"><img width="55px" height="55px"  src="<?= \common\components\FHtml::getImageUrl('social/whatsapp.png','www') ?>" alt="" title="" style="" class="animated"></i>
        </div>
    </a>
</div>
<style>

    .suntory-alo-phone {
        background-color: transparent;
        cursor: pointer;
        height: 80px;
        position: fixed;
        transition: visibility 0.5s ease 0s;
        width: 80px;
        z-index: 200000 !important;
    }

    .suntory-alo-ph-circle {
        animation: 1.2s ease-in-out 0s normal none infinite running suntory-alo-circle-anim;
        background-color: transparent;
        border: 2px solid rgba(30, 30, 30, 0.4);
        border-radius: 100%;
        height: 100px;
        left: 0px;
        opacity: 0.1;
        position: absolute;
        top: 0px;
        transform-origin: 50% 50% 0;
        transition: all 0.5s ease 0s;
        width: 100px;
    }
    .suntory-alo-ph-circle-fill {
        animation: 2.3s ease-in-out 0s normal none infinite running suntory-alo-circle-fill-anim;
        border: 2px solid transparent;
        border-radius: 100%;
        height: 70px;
        left: 15px;
        position: absolute;
        top: 15px;
        transform-origin: 50% 50% 0;
        transition: all 0.5s ease 0s;
        width: 70px;
    }
    .suntory-alo-ph-img-circle {
        /* animation: 1s ease-in-out 0s normal none infinite running suntory-alo-circle-img-anim; */
        border: 2px solid transparent;
        border-radius: 100%;
        height: 50px;
        left: 25px;
        opacity: 0.7;
        position: absolute;
        top: 25px;
        transform-origin: 50% 50% 0;
        width: 50px;
    }
    .suntory-alo-phone.suntory-alo-hover, .suntory-alo-phone:hover {
        opacity: 1;
    }
    .suntory-alo-phone.suntory-alo-active .suntory-alo-ph-circle {
        animation: 1.1s ease-in-out 0s normal none infinite running suntory-alo-circle-anim !important;
    }
    .suntory-alo-phone.suntory-alo-static .suntory-alo-ph-circle {
        animation: 2.2s ease-in-out 0s normal none infinite running suntory-alo-circle-anim !important;
    }
    .suntory-alo-phone.suntory-alo-hover .suntory-alo-ph-circle, .suntory-alo-phone:hover .suntory-alo-ph-circle {
        border-color: #00aff2;
        opacity: 0.5;
    }
    .suntory-alo-phone.suntory-alo-green.suntory-alo-hover .suntory-alo-ph-circle, .suntory-alo-phone.suntory-alo-green:hover .suntory-alo-ph-circle {
        border-color: #448aff;
        opacity: 1;
    }
    .suntory-alo-phone.suntory-alo-green .suntory-alo-ph-circle {
        border-color: #bfebfc;
        opacity: 1;
    }
    .suntory-alo-phone.suntory-alo-hover .suntory-alo-ph-circle-fill, .suntory-alo-phone:hover .suntory-alo-ph-circle-fill {
        background-color: rgba(0, 175, 242, 0.9);
    }
    .suntory-alo-phone.suntory-alo-green.suntory-alo-hover .suntory-alo-ph-circle-fill, .suntory-alo-phone.suntory-alo-green:hover .suntory-alo-ph-circle-fill {
        background-color: #448aff;
    }
    .suntory-alo-phone.suntory-alo-green .suntory-alo-ph-circle-fill {
        background-color: rgba(0, 175, 242, 0.9);
    }

    .suntory-alo-phone.suntory-alo-hover .suntory-alo-ph-img-circle, .suntory-alo-phone:hover .suntory-alo-ph-img-circle {
        background-color: #00aff2;
    }
    .suntory-alo-phone.suntory-alo-green.suntory-alo-hover .suntory-alo-ph-img-circle, .suntory-alo-phone.suntory-alo-green:hover .suntory-alo-ph-img-circle {
        background-color: #448aff;
    }
    .suntory-alo-phone.suntory-alo-green .suntory-alo-ph-img-circle {
        background-color: #4267B2;
    }
    @keyframes suntory-alo-circle-anim {
        0% {
            opacity: 0.1;
            transform: rotate(0deg) scale(0.5) skew(1deg);
        }
        30% {
            opacity: 0.5;
            transform: rotate(0deg) scale(0.7) skew(1deg);
        }
        100% {
            opacity: 0.6;
            transform: rotate(0deg) scale(1) skew(1deg);
        }
    }

    @keyframes suntory-alo-circle-img-anim {
        0% {
            transform: rotate(0deg) scale(1) skew(1deg);
        }
        10% {
            transform: rotate(-25deg) scale(1) skew(1deg);
        }
        20% {
            transform: rotate(25deg) scale(1) skew(1deg);
        }
        30% {
            transform: rotate(-25deg) scale(1) skew(1deg);
        }
        40% {
            transform: rotate(25deg) scale(1) skew(1deg);
        }
        50% {
            transform: rotate(0deg) scale(1) skew(1deg);
        }
        100% {
            transform: rotate(0deg) scale(1) skew(1deg);
        }
    }
    @keyframes suntory-alo-circle-fill-anim {
        0% {
            opacity: 0.2;
            transform: rotate(0deg) scale(0.7) skew(1deg);
        }
        50% {
            opacity: 0.2;
            transform: rotate(0deg) scale(1) skew(1deg);
        }
        100% {
            opacity: 0.2;
            transform: rotate(0deg) scale(0.7) skew(1deg);
        }
    }
    .suntory-alo-ph-img-circle i {
        animation: 1s ease-in-out 0s normal none infinite running suntory-alo-circle-img-anim;
        font-size: 30px;
        line-height: 40px;
        padding-left: 12px;
        color: #fff;
        display:block;
    }
    /*=================== End phone ring ===============*/
    @keyframes suntory-alo-ring-ring {
        0% {
            transform: rotate(0deg) scale(1) skew(1deg);
        }
        10% {
            transform: rotate(-25deg) scale(1) skew(1deg);
        }
        20% {
            transform: rotate(25deg) scale(1) skew(1deg);
        }
        30% {
            transform: rotate(-25deg) scale(1) skew(1deg);
        }
        40% {
            transform: rotate(25deg) scale(1) skew(1deg);
        }
        50% {
            transform: rotate(0deg) scale(1) skew(1deg);
        }
        100% {
            transform: rotate(0deg) scale(1) skew(1deg);
        }
    }

    @media(max-width: 768px){
        .suntory-alo-phone{
            display: block;
        }
    }
</style>
<?php } ?>

