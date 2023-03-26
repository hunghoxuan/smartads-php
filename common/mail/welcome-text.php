<?php

use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $user \backend\modules\app\models\AppUserAPI */

$email = $user->email;
$token = isset($user->password_reset_token) ? $user->password_reset_token : rand(111111, 999999);
$base_url = rtrim(FHtml::currentBaseURL(), '/');
$confirm_url = $base_url . "/index.php/site/confirm-registration?email=$email&token=$token"
?>

Dear <?= $user->username ?>,

Welcome to <?=  Yii::$app->name ?>,

Your registration is completed!

Please click below button to confirm your registration
<?= $confirm_url ?>

Thank you for using our services,

<?=  Yii::$app->name ?>.