<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$id = FHtml::getRequestParam(['id']);
$object_type = FHtml::getRequestParam(['object_type'], 'app_user');

$download = FHtml::getRequestParam(['action']) == 'download' || !empty(FHtml::getRequestParam(['download']));

$name = FHtml::getRequestParam(['name']);
$dob = FHtml::getRequestParam(['dob']);
$email = FHtml::getRequestParam(['email']);
$phone = FHtml::getRequestParam(['phone']);
$address = FHtml::getRequestParam(['address']);
$file = FHtml::getFriendlyFileName($name);

$text = \common\components\FHtml::getVCardContent($name, $dob, $email, $phone, $address);

if ($download) {
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$file.vcf");
}
echo $text;
die;

?>