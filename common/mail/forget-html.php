<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/20/2016
 * Time: 5:08 PM
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $token */

$name = isset($name) ? $name : 'Client';


?>
<div class="forget-password">
    <p>Dear <?= $name ?>,</p> <br/>
    <p>Your reset password link is : <i> <?=  Html::a('Change password page', Yii::$app->getUrlManager()->getHostInfo().Yii::$app->urlManagerFrontend->createUrl('index.php/site/change-password')) ?>
        </i></p>
    <p>Please use this token for reset password : <?= $token ?> </p>

    <p>BR,</p>
    <p><b><?= \common\components\FHtml::settingCompanyName() ?></b></p>
</div>