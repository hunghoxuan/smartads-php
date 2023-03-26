<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\FConstant;
use common\components\FApi;
use Yii;

class ForgetPasswordAction extends BaseAction
{
    public $is_secured = false;

    public function run()
    {
        $email = FApi::getRequestParam('email', '');

        if (strlen($email) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        $checkEmail = AppUserAPI::find()->where("email = '" . $email . "'")->one();

        if (!isset($checkEmail)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(223), ['code' => 223]);
        }
        /* @var AppUserAPI $checkEmail */
        $token = md5(time() . $checkEmail->id);
        $checkEmail->password_reset_token = $token;
        $checkEmail->save();


        if (!AppUserAPI::isPasswordResetTokenValid($checkEmail->password_reset_token)) {
            $checkEmail->generatePasswordResetToken();
        }

        if ($checkEmail->save()) {
            $send = Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'passResetToken-html', 'text' => 'passResetToken-text'],
                    ['user' => $checkEmail]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
            if ($send) {
                return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK');
            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(229), ['code' => 229]);
            }
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(201), ['code' => 201]);
        }
    }
}
