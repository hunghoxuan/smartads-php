<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$this->title = 'Login';
?>
<div class="row" style="height: 100%">
    <div class="col-md-8 col-xs-0"
         style="height:100%;background-repeat: no-repeat; background-position: center center; background-size: cover;background-image: url('<?= FHtml::getAdminLoginBackgroudUrl() ?>');">&nbsp;
    </div>
    <div class="col-md-4 col-xs-12" style="background-color: white; height: 100%">
        <div class="content" style="width:100% !important; margin-top: 100px">
            <div class="logo" style="margin:0px !important">
                <?= \common\components\FHtml::showCurrentLogo('', '80px', '', '') ?>
            </div>
            <!-- BEGIN LOGIN FORM -->

            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
                'class' => 'login-form'
            ]]); ?>
            <h3 class="form-title"
                style="text-align: center; margin-bottom: 80px"><?php if (APPLICATIONS_ENABLED) echo FHtml::currentCompanyName(); else echo FHtml::t('common', 'User Login') ?></h3>
            <hr/>
            <h5><?= FHtml::t('common', 'Login using Username or Email') ?></h5>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span> Enter any username and password. </span>
            </div>

            <?= $form->field($model, 'username', ['template' => "{label}\n<label class=\"control-label visible-ie8 visible-ie9\">Username</label>\n<div class=\"input-icon\"><i class=\"fa fa-user\"></i>\n{input}\n</div>\n{hint}\n{error}"])
                ->textInput([
                    'autofocus' => true,
                    'placeholder' => 'Username',
                    'class' => 'form-control placeholder-no-fix',
                    'autocomplete' => 'off'
                ])
                ->label(false)
            ?>

            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <?= $form->field($model, 'password', ['template' => "{label}\n<label class=\"control-label visible-ie8 visible-ie9\">Password</label>\n<div class=\"input-icon\"><i class=\"fa fa-lock\"></i>\n{input}\n</div>\n{hint}\n{error}"])
                    ->passwordInput([
                        'autofocus' => true,
                        'placeholder' => 'Password',
                        'class' => 'form-control placeholder-no-fix',
                        'autocomplete' => 'off'
                    ])
                    ->label(false)
                ?>
            </div>

            <div class="form-actions">
                <?= $form->field($model, 'rememberMe', ['template' => "<label class=\"rememberme mt-checkbox mt-checkbox-outline\">\n{input}\nRemember Me<span></span></label>\n{error}", 'options' => ['class' => 'col-xs-8']])->checkbox([], false) ?>

                <?= Html::submitButton('Login', ['class' => 'btn green pull-right', 'name' => 'login-button']) ?>

            </div>
            <?php ActiveForm::end(); ?>

            <div class="login-options">
                <h4>Or access via</h4>
                <ul class="social-icons">
                    <li>
                        <a class="social-icon-color facebook" data-original-title="facebook"
                           href="javascript:;"></a>
                    </li>
                    <li>
                        <a class="social-icon-color twitter" data-original-title="Twitter" href="javascript:;"></a>
                    </li>
                    <li>
                        <a class="social-icon-color googleplus" data-original-title="Goole Plus"
                           href="javascript:;"></a>
                    </li>
                    <li>
                        <a class="social-icon-color linkedin" data-original-title="Linkedin"
                           href="javascript:;"></a>
                    </li>
                </ul>
            </div>
            <!-- END LOGIN FORM -->
            <div class="copyright" style="margin-top:50px;color:darkgrey"> <?= \common\components\FHtml::settingCompanyPowerby() ?> </div>

        </div>

    </div>
</div>

