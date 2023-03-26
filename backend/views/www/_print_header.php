<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;
use backend\assets\CustomAsset;


$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$form_type = isset($form_type) ? $form_type : '';

/* @var $this yii\web\View */
/* @var $model backend\models\AppUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row visible-print">
    <table class="" style="width:100%">
        <tr style="border-bottom: 1px dashed lightgrey">
            <td class="col-xs-2 invoice-logo-space">
                <?= FHtml::showCurrentLogo() ?>
            </td>
            <td class="col-xs-6 invoice-logo-space" style="font-size:8pt; color:grey">
                <b><?= FHtml::settingCompanyName() ?></b><br/>
                <small>
                    <?= FHtml::settingCompanyAddress() ?><br/>
                    <?= FHtml::settingCompanyPhone() ?>
                    - <?= FHtml::config(FHtml::SETTINGS_COMPANY_CHAT) ?><br/>
                </small>
            </td>
            <td class="col-xs-4" style="font-size:8pt">
                <p class="pull-right">
                    <small class="text-right">
                        <?= $form_type . ' #' . FHtml::getRequestParam('id') ?><br/>
                        <?= date('Y-m-d') ?><br/>
                        <?= FHtml::showCurrentUser() ?><br/>
                    </small>
                </p>
            </td>
        </tr>
    </table>
    <br/>
    <h3 class="text-center" style="margin:20px"><b><?= $form_type ?> - <?= $title ?></b></h3>
    <br/>
</div>



