<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/20/2016
 * Time: 5:08 PM
 */
/* @var $this yii\web\View */
/* @var $token */
$name = isset($name) ? $name : 'Client';
?>
Dear <?= $name ?>,

Your reset password link is:
<?= \yii\helpers\Html::a('Change password page', Yii::$app->getUrlManager()->getHostInfo().Yii::$app->urlManagerFrontend->createUrl('index.php/site/change-password')) ?>

Please use this token for reset password :
<?= $token ?>

Regards,
<?=  Yii::$app->name ?> ?>