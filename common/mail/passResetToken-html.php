<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-pass', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Dear <?= Html::encode($user->username) ?>,</p>

    <p>Please Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
    <br/>
    Regards, <br/>
    <?=  Yii::$app->name ?>
</div>
