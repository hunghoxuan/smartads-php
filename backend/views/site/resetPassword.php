<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ResetPasswordForm */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <h3 class="form-title"><?= Html::encode($this->title) ?></h3>
    <?= Alert::widget() ?>
    <p>
        <?= \common\components\FHtml::t('common', 'Please enter your new password') ?>
    </p>
    <?= $form->field($model, 'password', ['template' => "{label}\n<label class=\"control-label visible-ie8 visible-ie9\">Password</label>\n<div class=\"input-icon\"><i class=\"fa fa-lock\"></i>\n{input}\n</div>\n{hint}\n{error}"])
        ->passwordInput([
            'autofocus' => true,
            'placeholder' => 'Password',
            'class' => 'form-control placeholder-no-fix',
            'autocomplete' => 'off'
        ])
        ->label(false)
    ?>
    <div class="form-actions">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="">
        <p> Back to <a href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>" id="register-btn"> Login </a></p>
    </div>
    <?php ActiveForm::end(); ?>
</div>