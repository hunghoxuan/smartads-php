<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/20/2016
 * Time: 5:08 PM
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $params */

$resetLink = 'google.com.vn';
?>
<div class="password-reset">
    <p><?= $params ?> Cuong,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>