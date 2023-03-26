<?php

use common\components\FHtml;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$new_user = new \backend\modules\app\models\AppUserAPI();

$new_user->name = 'HAHAHAHA';
$new_user->is_active = 1;
$new_user->email = 'ehee' . "@server3.hotelwifi.in";
$new_user->status = \common\components\FConstant::LABEL_NORMAL;
$new_user->balance = 0;
$new_user->rate = 0;
$new_user->rate_count = 0;
$new_user->type = \backend\modules\app\models\AppUserAPI::TYPE_USER;
$new_user->status = \backend\modules\app\models\AppUserAPI::STATUS_NORMAL;
$reset_token = md5(time());
$new_user->password_reset_token = $reset_token;
$new_user->generateAuthKey();
$new_user->setPassword('123456');
$new_user->created_date = date('Y-m-d');
if ($new_user->save()) {
    echo 'OK'; die;
}else {
    FHtml::var_dump($new_user);
    var_dump($new_user->getErrors());die;
}




?>

