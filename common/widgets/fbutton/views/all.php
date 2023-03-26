<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 02/08/2017
 * Time: 17:09 CH
 */

$skype = \common\components\FHtml::settingCompanyChat(false);
if (empty($skype))
    $skype = 'live:mozasolution';
$phone = \common\components\FHtml::settingCompanyPhone();
//if (empty($skype))
//    $skype = 'tel:' . $phone;
//else
    if (!\yii\helpers\StringHelper::startsWith($skype, 'skype:'))
        $skype = 'skype:' . $skype . '?chat';

$btn_url = ( !empty($btn_url) || !isset($btn_url)) ? $btn_url : $skype ;
$style = ( !empty($style) || !isset($style)) ? $style : 'right :0;';
?>

<div style="background-color: white">
<a href="https://www.messenger.com/t/moonworkcoworking" class="suntory-alo-phone suntory-alo-green" id="suntory-zalo-phoneIcon" style="right: 100px; bottom: 0px;" target="_blank">
    <div class="suntory-alo-ph-circle-fill"></div>
    <div class="suntory-alo-ph-img-circle">
        <i class="img-fb"><img src="<?= \common\components\FHtml::getImageUrl('social/skype.png','www') ?>" alt="" title="" style="opacity: 500;" class="animated"></i>
    </div>
</a>

<a href="https://www.messenger.com/t/moonworkcoworking" class="suntory-alo-phone suntory-alo-green" id="suntory-zalo-phoneIcon" style="right: 0px; bottom: 0px;" target="_blank">
    <div class="suntory-alo-ph-circle-fill"></div>
    <div class="suntory-alo-ph-img-circle">
        <i class="img-fb"><img src="<?= \common\components\FHtml::getImageUrl('social/facebook.png','www') ?>" alt="" title="" style="opacity: 500;" class="animated"></i>
    </div>
</a>
</div>
<style>
    i.img-fb{margin:-5px -19px;}
    i.img-phone { margin: 0px 18px;}

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
