<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$this->title = 'Login';
$login_style = FHtml::settingBackendLoginPosition('center');

?>
<style>
    .content {
        top:50px;
    }
    @media (max-width: 440px)
        .content {
            width: 100% !important;
        }

</style>
<?php if ($login_style == 'right' || $login_style == 'left') { ?>
<style>
    .login-form {
        height : 100%;
    }
    .login-form, .content-full {
        background-color: white;
        height: 100%;
        padding: 20px;
    }
    .content {
        margin-top: 10%; width:50% !important;
    }
</style>
<?php } ?>

<div class="row" style="<?= ($login_style == 'right' || $login_style == 'left') ? 'height:100%;' : ''; ?>">
    <?php if ($login_style == 'right') { ?>
    <div class="content-full col-md-4 col-xs-12 pull-right">
        <?php } else if ($login_style == 'left') { ?>
        <div class="content-full col-md-4 col-xs-12 pull-left">
            <?php } else { ?>
            <div class="content">
                <?php } ?>

                <div class="logo" style="margin:0px !important">
                    <?php $logo = \common\components\FHtml::showCurrentLogo('60%', '', '', '');
                    if (!empty($logo))
                        echo $logo;
                    ?>

                </div>
                <h3 class="form-title" style="text-align: center; margin-bottom: 20px">
                    <?php

                    if (true || APPLICATIONS_ENABLED)
                        echo FHtml::currentCompanyName();
                    else
                        echo FHtml::t('common', 'User Login');

                    ?>
                </h3>
                <br/>
                <!-- BEGIN LOGIN FORM -->
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
                    'class' => 'login-form'
                ]]); ?>

                <?php echo FHtml::showCurrentMessages() ?>

                <h5><?= FHtml::t('common', 'Login using Username or Email') ?> </h5>
                <?= $form->field($model, 'username', ['template' => "{label}\n<label class=\"control-label visible-ie8 visible-ie9\">Username</label>\n<div class=\"input-icon\"><i class=\"fa fa-user\"></i>\n{input}\n</div>\n{hint}\n{error}"])
                    ->textInput([
                        'autofocus' => false,
                        'placeholder' => 'Username',
                        'class' => 'form-control placeholder-no-fix',
                        'autocomplete' => 'off'
                    ])
                    ->label(false)
                ?>

                <?= $form->field($model, 'password', ['template' => "{label}\n<label class=\"control-label visible-ie8 visible-ie9\">Password</label>\n<div class=\"input-icon\"><i class=\"fa fa-lock\"></i>\n{input}\n</div>\n{hint}\n{error}"])
                    ->passwordInput([
                        'autofocus' => false,
                        'placeholder' => 'Password',
                        'class' => 'form-control placeholder-no-fix',
                        'autocomplete' => 'off'
                    ])
                    ->label(false)
                ?>

                <div class="row">
                    <div style="padding-left:12px; padding-right:8px">
                        <?= $form->field($model, 'rememberMe', ['template' => "<label class=\"rememberme mt-checkbox mt-checkbox-outline\">\n{input}\nRemember Me<span></span></label>\n{error}", 'options' => ['class' => 'col-xs-8']])->checkbox([], false) ?>
                    </div>
                </div>
                <div class="row">
                    <div style="padding-left:12px; padding-right:8px">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-lg green pull-left col-md-12', 'name' => 'login-button']) ?>
                    </div>
                </div>
                <hr/>
                <div style="margin-top:20px">
                    <?php $social_login =  FHtml::settingBackendSocialLogin();
                    if (!empty($social_login)) { ?>
                        <div style="margin-left:-40px !important;">
                            <?= yii\authclient\widgets\AuthChoice::widget([
                                'baseAuthUrl' => ['site/auth'],
                                'popupMode' => false,
                            ]) ?>
                        </div>
                    <?php } ?>

                    <a href="<?= Yii::$app->urlManager->createUrl(['/site/request-password-reset']) ?>" id="forget-password">
                        <?= FHtml::t('common', 'Reset Password') ?> </a>
                </div>
                <?php ActiveForm::end(); ?>

                <!-- END LOGIN FORM -->
                <?php $copyright = \common\components\FHtml::settingCompanyPowerby();
                if (strlen($copyright) != 0) { ?>
                    <div class="copyright" style="margin-top:30px; margin-bottom: 0px; padding: 0px; color:darkgrey; font-size:70%"> <?= $copyright ?> </div>
                <?php } ?>
            </div>
        </div>
