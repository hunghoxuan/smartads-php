<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "Book".
 */
use common\components\FHtml;
use common\widgets\FActiveForm;
use common\widgets\FFormTable;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormObjectFile;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

/* @var $this yii\web\View */
/* @var $model backend\modules\book\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

