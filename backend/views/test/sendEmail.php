<?php

use common\components\FHtml;
use yii\helpers\Url;


/* @var $this yii\web\View */

//\common\components\FEmail::sendEmailFromAdmin('ceo@mozagroup.com', 'Hung Ho', 'Title', 'HAHAHAA');
$model = new \applications\stechqmsvcb\models\EcommerceOrder();
$model = $model::createDummy();

$send = \Yii::$app->mailer->compose([
    'html'       => 'forget-html',
], ['model' => $model, 'token' => '0001'])->setFrom(['hahaa@gmail.com' => 'TEST MAIL'])->setTo(['ceo@mozagroup.com', 'hoanganhken97@gmail.com'])
    ->setSubject('Mail hủy đăng ký vé POC')->send();
?>

