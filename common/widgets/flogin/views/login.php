<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data array
 */
use common\components\FHtml;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use backend\assets\CustomAsset;
/* @var $model \common\models\LoginFormFrontend */
/* @var $model2 \frontend\models\SignupForm */


//$asset = \frontend\assets\CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = FHtml::currentUserIdentity();

if (!isset($model)) {
    $model = new \common\models\LoginFormFrontend();
}
if (!isset($model2)) {
    $model2 = new \frontend\models\SignupForm();
}
$allowAdminLogin = isset($allowAdminLogin) ? $allowAdminLogin : false;

?>

<link rel="stylesheet"
      href="<?php echo $baseUrl ?>/common/assets/plugins/login-signup-modal-window/css/style.css">

<?php if (!$user) { ?>

    <div class="cd-user-modal rounded"> <!-- this is the entire modal form, including the background -->
        <div class="cd-user-modal-container"> <!-- this is the container wrapper -->
            <ul class="cd-switcher">
                <li><a href="javascript:void(0);">Login</a></li>
                <li><a href="javascript:void(0);">Register</a></li>
            </ul>
            <div id="cd-login">
                <div id="login-message" class="error text-center" style="color:red;"></div>
                <?php $form = ActiveForm::begin(['id' => 'login-form',
                    'action' => FHtml::createUrl('{controller}/login'),
                    'options' => [
                        'class' => 'cd-form',
                    ]]); ?>

                <p class="social-login" style="padding-top: 30px">
                    <span class="social-login-facebook"><a class="facebook auth-link" target="_blank"
                                                           href="<?php echo FHtml::createUrl('site/auth', ['authclient' => 'facebook']) ?>"><i
                                class="fa fa-facebook"></i> Facebook</a></span>
                    <!--
                    <span class="social-login-google"><a href="<?php echo FHtml::createUrl('site/auth', ['authclient' => 'google']) ?>"><i class="fa fa-google"></i> Google</a></span>
                    <span class="social-login-twitter"><a href="#"><i class="fa fa-twitter"></i> Twitter</a></span>
                    -->
                </p>

                <p class="cd-form-bottom-message"><a href="javascript:void(0);">Forgot your password?</a></p>

                <div class="lined-text" style="margin-bottom: 30px"><span>Or use your account</span>
                    <hr>
                </div>
                <?= $form->field($model, 'email',
                    ['template' => "
                <p class=\"fieldset\">\n
                {label}\n<label class=\"image-replace cd-email\" for=\"signin-email\">E-mail</label>\n
                {input}\n
                {hint}\n
                {error}\n
                 </p>
                "])
                    ->textInput([
                        'autofocus' => true,
                        'placeholder' => 'E-mail',
                        'class' => 'full-width has-padding has-border rounded',
                        'autocomplete' => 'off'
                    ])
                    ->label(false)
                ?>
                <div class="input-icon">
                    <?= $form->field($model, 'password', ['template' => "
                 <p class=\"fieldset\">\n
                {label}\n <label class=\"image-replace cd-password\" for=\"signin-password\">Password</label>
                \n{input}
                \n<a href=\"javascript:void(0);\" class=\"hide-password\">Hide</a>
                \n{hint}
                \n{error}
                \n</p>
                "])
                        ->passwordInput([
                            'autofocus' => true,
                            'placeholder' => 'Password',
                            'class' => 'full-width has-padding has-border rounded',
                            'autocomplete' => 'off'
                        ])
                        ->label(false)
                    ?>
                </div>
                <?= $form->field($model, 'rememberMe', ['template' => "<label class=\"rememberme mt-checkbox mt-checkbox-outline\">\n{input}\nRemember Me<span></span></label>\n{error}", 'options' => ['class' => 'col-xs-8']])->checkbox([], false) ?>
                <?= $allowAdminLogin ? $form->field($model, 'asAdmin', ['template' => "<label class=\"rememberme mt-checkbox mt-checkbox-outline\">\n{input}\nLogin as Admin ?<span></span></label>\n{error}", 'options' => ['class' => 'col-xs-8']])->checkbox([], false) : '' ?>
                <?= Html::button('Login', ['class' => 'full-width', 'name' => 'login-button',
                    'href' => '#',
                    'onclick' => 'login($("#loginformfrontend-email").val(), $("#loginformfrontend-password").val(), $("#loginformfrontend-asadmin").is(":checked"))',
                    'style' => [
                        'padding' => '16px 0',
                        'cursor' => 'pointer',
                        'color' => '#fff',
                        'font-weight' => '200',
                        'background' => '#333',
                        'font-size' => '16px',
                        'border' => 'none',
                        'text-transform' => 'uppercase',
                        'border-radius' => '5px',
                        '-webkit-appearance' => 'none'
                    ]
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div> <!-- cd-login -->

            <div id="cd-signup">
                <div id="login-message" class="error text-center" style="color:red;"></div>
                <!-- sign up form -->
                <?php $form = ActiveForm::begin(['id' => 'signup-form',
                    'action' => FHtml::createUrl('{controller}/signup'),
                    'options' => [
                        'class' => 'cd-form',
                    ]]); ?>

                <p class="social-login">
                    <span class="social-login-facebook"><a href="<?php echo FHtml::createUrl('site/auth', ['authclient' => 'facebook']) ?>"><i class="fa fa-facebook"></i> Facebook</a></span>
                    <!--
                    <span class="social-login-google"><a href="#"><i class="fa fa-google"></i> Google</a></span>
                    <span class="social-login-twitter"><a href="#"><i class="fa fa-twitter"></i> Twitter</a></span>
                    -->
                </p>

                <div class="lined-text" style="margin-bottom: 30px"><span>Or register your new account</span>
                    <hr>
                </div>

                <?= $form->field($model2, 'email',
                    ['template' => "
                <p class=\"fieldset\">\n
                {label}\n<label class=\"image-replace cd-email\" for=\"signin-email\">E-mail</label>\n
                {input}\n
                {hint}\n
                {error}\n
                 </p>
                "])
                    ->textInput([
                        'autofocus' => true,
                        'placeholder' => 'E-mail',
                        'class' => 'full-width has-padding has-border rounded',
                        'autocomplete' => 'off'
                    ])
                    ->label(false)
                ?>

                <div class="input-icon">
                    <?= $form->field($model2, 'password', ['template' => "
                 <p class=\"fieldset\">\n
                {label}\n <label class=\"image-replace cd-password\" for=\"signin-password\">Password</label>
                \n{input}
                 \n<a href=\"javascript:void(0);\" class=\"hide-password\">Hide</a>
                \n{hint}
                \n{error}
                \n</p>
                "])
                        ->passwordInput([
                            'autofocus' => true,
                            'placeholder' => 'Password',
                            'class' => 'full-width has-padding has-border rounded',
                            'autocomplete' => 'off'
                        ])
                        ->label(false)
                    ?>
                </div>
                <?= Html::button('Signup', ['class' => 'full-width', 'name' => 'signup-button',
                    'href' => '#',
                    'onclick' => 'signup($("#signupform-email").val(), $("#signupform-password").val())',

                    'style' => [
                        'padding' => '16px 0',
                        'cursor' => 'pointer',
                        'color' => '#fff',
                        'font-weight' => '200',
                        'background' => '#333',
                        'font-size' => '16px',
                        'border' => 'none',
                        'text-transform' => 'uppercase',
                        '-webkit-appearance' => 'none',
                        'border-radius' => '5px',
                    ]
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div> <!-- cd-signup -->

            <div id="cd-reset-password"> <!-- reset password form -->
                <p class="cd-form-message">Lost your password? Please enter your email address. You will receive a link
                    to create a new password.</p>

                <form class="cd-form">
                    <p class="fieldset">
                        <label class="image-replace cd-email" for="reset-email">E-mail</label>
                        <input class="full-width has-padding has-border" id="reset-email" type="email"
                               placeholder="E-mail">
                        <span class="cd-error-message" style="margin-top: -5px">Error message here!</span>
                    </p>

                    <p class="fieldset">
                        <input class="full-width has-padding" type="submit" value="Reset password">
                    </p>
                </form>

                <p class="cd-form-bottom-message"><a href="javascript:void(0);">Back to log-in</a></p>
            </div> <!-- cd-reset-password -->
            <a href="javascript:void(0);" class="cd-close-form">Close</a>
        </div> <!-- cd-user-modal-container -->
    </div> <!-- cd-user-modal -->

<?php } ?>

<script>
    function login($username, $password, $asAdmin) {
        $.ajax({
            url: '<?= FHtml::createUrl('login', []) ?>?email=' + $username + '&password=' + $password + '&asAdmin=' + $asAdmin,
            type: 'post',
            success: function (data) {
                if (data == 1 || data == '')
                    reloadPage();
                else
                    message(data);
            }
        });
    }

    function signup($username, $password) {
        $.ajax({
            url: '<?= FHtml::createUrl('signup', []) ?>?email=' + $username + '&password=' + $password,
            type: 'post',
            success: function (data) {
                if (data == 1 || data == '')
                    reloadPage();
                else
                    message(data);
            }
        });
    }

    function message(data) {
        $('#login-message').html('<br/>' + data);
    }

    function reloadPage() {
        location.reload();
    }
</script>

<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/login-signup-modal-window/js/main.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

