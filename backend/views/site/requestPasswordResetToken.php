<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <h3 class="form-title"><?= Html::encode($this->title) ?></h3>
    <?= Alert::widget() ?>
    <p>
        <?= \common\components\FHtml::t('common', 'Please fill out your email. A link to reset password will be sent there.') ?>

    </p>
    <?= $form->field($model, 'email', ['template' => "{label}\n<label class=\"control-label visible-ie8 visible-ie9\">Email</label>\n<div class=\"input-icon\"><i class=\"fa fa-envelope\"></i>\n{input}\n</div>\n{hint}\n{error}"])
        ->textInput([
            'autofocus' => true,
            'placeholder' => 'Email',
            'class' => 'form-control placeholder-no-fix',
            'autocomplete' => 'off'
        ])
        ->label(false)
    ?>

    <div class="form-actions">
        <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="">
        <p> Back to <a href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>" id="register-btn"> Login </a></p>
    </div>
    <?php ActiveForm::end(); ?>

</div>
