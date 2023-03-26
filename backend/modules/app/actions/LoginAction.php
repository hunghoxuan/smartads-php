<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppDeviceAPI;
use backend\modules\app\models\AppTokenAPI;
use backend\modules\app\models\AppUserAPI;
use common\components\FConstant;
use common\components\FApi;
use common\components\FHtml;
use Yii;

class LoginAction extends BaseAction
{
    public $is_secured = false;

    public function run()
    {
        $username = FApi::getRequestParam('username', '');
        $password = FApi::getRequestParam('password', '');
        $imei = FApi::getRequestParam(['imei', 'ime']);
        $token = FApi::getRequestParam(['token', 'gcm_id']);
        $device_type = FApi::getRequestParam(['type', 'device_type'], '');
        $login_type = FApi::getRequestParam('login_type', '');
        $name = FApi::getRequestParam('name', '');
        $avatar = FApi::getRequestParam('avatar_url', '');
        $user_type = FApi::getRequestParam('user_type', 'user');

        if (
            strlen($username) == 0
            //login type
            || ($login_type == 'n' && strlen($password) == 0)
            || ($login_type == 's' && strlen($name) == 0)
        ) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        /* @var \backend\modules\app\models\AppUserAPI $check */
        /* @var \backend\modules\app\models\AppUserAPI $checkEmail */

        $checkEmail = AppUserAPI::find()->where(["username" => $username])->one();
        $today = date('Y-m-d H:i:s', time());

        if ($login_type == 'n') { //normal
            if (!isset($checkEmail)) {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(223), ['code' => 223]);
            }
        } else { //social
            if (!$checkEmail) {
                $check = new AppUserAPI();
                $check->name = $name;
                $check->avatar = $avatar;
                $check->email = $username;
                $check->username = $username;
                $check->is_active = FConstant::STATE_ACTIVE;
                $check->status = FConstant::LABEL_NORMAL;
                $check->created_date = $today;
                $check->type = AppUserAPI::TYPE_USER;
                $check->status = AppUserAPI::STATUS_NORMAL;
                $random = FHtml::generateRandomCode(6);
                $reset_token = md5(time());
                $check->password_reset_token = $reset_token;
                $check->setPassword($random);
                $check->generateAuthKey();
                $check->save();

                \Yii::$app->mailer->compose(['html' => 'welcome-social-html', 'text' => 'welcome-social-text', 'htmlLayout' => '@layouts/welcome-html.php'],
                    [
                        'email' => $username,
                        'password' => $random
                    ]
                )
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($username)
                    ->setSubject('[' . Yii::$app->name . '] Welcome new member')
                    ->send();

            } else {
                $checkEmail->avatar = $avatar;
                $checkEmail->name = $name;
                $checkEmail->save();
            }
        }

        $check = AppUserAPI::findByUsername($username);

        if (isset($check)) {
            if ($login_type == 'n') {
                if (!$check->validatePassword($password)) {
                    return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::WRONG_PASSWORD, ['code' => 222]);
                }
            }
            $user_id = $check->id;

            $login_token = md5($user_id . time());

            /* @var \backend\modules\app\models\AppDeviceAPI $checkDevice */
            /* @var \backend\modules\app\models\AppTokenAPI $loginToken */

            if (strlen($imei) != 0 && strlen($device_type) != 0) {
                $checkDevice = AppDeviceAPI::find()->where(["imei" => $imei])->one();
                if (isset($checkDevice)) {
                    $checkDevice->user_id = $user_id;
                    $checkDevice->token = $token;
                    $checkDevice->save();
                } else {
                    $checkDevice = new AppDeviceAPI();
                    $checkDevice->user_id = $user_id;
                    $checkDevice->imei = $imei;
                    $checkDevice->is_active = 1;
                    $checkDevice->token = $token;
                    $checkDevice->type = $device_type;
                    $checkDevice->save();
                }
            }

            $loginToken = AppTokenAPI::find()->where(['user_id' => $check->id])->one();
            if (isset($loginToken)) {
                $loginToken->token = $login_token;
                $loginToken->time = time();
                $loginToken->save();
            } else {
                $loginToken = new AppTokenAPI();
                $loginToken->user_id = $user_id;
                $loginToken->token = $login_token;
                $loginToken->time = time();
                $loginToken->save();
            }

            $check->is_online = FConstant::STATE_ACTIVE;
            $check->type = $user_type;
            $check->save();

            Yii::$app->response->statusCode = 200;
            return FApi::getOutputForAPI($check, FConstant::SUCCESS, 'OK', [
                'login_token' => $loginToken->token,
                'code' => 200,
                'time' => $today,
                'object_type' => AppUserAPI::tableName()
            ]);
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(228), ['code' => 228]);
        }
    }
}
