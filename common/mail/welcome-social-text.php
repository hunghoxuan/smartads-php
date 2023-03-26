<?php

/* @var $this yii\web\View */
/* @var $email string */
/* @var $password string */


?>

    Hello,

    Welcome to <?= Yii::$app->name ?>
    Your account has been created successfully!
    You can login by your social account or this authentication info:

    Username: <?= $email ?>
    Password: <?= $password ?>

    You can reset or change our password after first login.

    Thank you for using our services,

    <?= Yii::$app->name ?>