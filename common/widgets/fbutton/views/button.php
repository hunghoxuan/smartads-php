<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 02/08/2017
 * Time: 15:49 CH
 */
use backend\assets\CustomAsset;
use common\components\FHtml;

$application = FHtml::currentApplicationId();
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= "/applications/$application/assets/";
$user = Yii::$app->user->identity;


$background_css = ( !empty($background_css) || !isset($background_css)) ? $background_css : '' ;
$title = (!empty($title) || !isset($title)) ? $title :  "Please contact us for more details";
$btn_label = (!empty($btn_label) || !isset($btn_label)) ? $btn_label :  "Contact Us";
$btn_url = (!empty($btn_url) || !isset($btn_url)) ? $btn_url :  "#";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/css/hover.css">

<style>
    .cta-title{
        font-size: 20px;
        color: #fff;
        margin: 32px 0 0;
        font-weight: 700;
    }
    .getcontact{
        margin: 20px 0 20px 35px;
        background: transparent;
        color: #fff;
        border-color: #fff;
        padding: 0 30px;
        border: 2px solid;
        line-height: 46px;
        font-weight: 700;
        text-transform: capitalize;
        position: relative;
        z-index: 2;
        display: inline-block;
        transition: all 0.3s ease-in-out;
        box-shadow: none;

    }
    .page {
        margin-top: 30px;
        padding-top: 20px;
        padding-bottom: 20px;
        <?= $background_css ?>
    }
    .fix {
        overflow: hidden;
    }
</style>

<div class="page fix" style="background-color: #01AAC1">
    <div class="container">
        <div class="row" style="height: 90px;">
            <div class="col-md-6 col-md-offset-2 col-xs-12 cta-txt">
                <h5 class="cta-title "><?= $title ?></h5>
            </div>
            <div class="col-md-4 col-xs-12 cta-txt">

                <a class="getcontact button hvr-shutter-out-horizontal" href="<?= $btn_url ?>"><?= $btn_label ?></a>

            </div>
        </div>
    </div>
</div>
