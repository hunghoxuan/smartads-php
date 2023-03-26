<?php
use common\components\FHtml;
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use yii\captcha\Captcha;
use backend\assets\CustomAsset;
//$baseUrl = \common\components\FHtml::getBaseUrl($this);
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
if (!isset($model))
    $model = new \frontend\models\ContactForm();
?>

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'title') ?>

<?= $form->field($model, 'content')->textarea(['rows' => 3])->label(FHtml::t('common', 'Message')) ?>

<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
    'captchaAction' => '/captcha', //important
    'options' => ['placeholder' => FHtml::t('common', 'Enter verification code'), 'class' => 'form-control'],
    'template' => '<div class="row"><div class="col-md-2">{image}</div><div class="col-md-10">{input}</div></div>',
])->label(false) ?>

<div class="form-group contact-form">
 <input type="submit" class="submit" value="<?= FHtml::t('common','Submit') ?>" name="contact-button"/>
</div>

<?php ActiveForm::end(); ?>


