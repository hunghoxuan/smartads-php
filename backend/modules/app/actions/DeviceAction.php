<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppDeviceAPI;
use backend\modules\app\models\AppUserAPI;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;

class DeviceAction extends BaseAction
{
    public function run()
    {
        $imei = FApi::getRequestParam(['imei', 'ime'], '');
        $token = FApi::getRequestParam(['token', 'gcm_id'], '');
        $type = FApi::getRequestParam('type', ''); //android/ios
        $is_active = FApi::getRequestParam(['is_active', 'status'], 1);

        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $user_id = $this->user_id;

        if (strlen($imei) == 0 || strlen($token) == 0 || strlen($type) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }


        $check = AppDeviceAPI::find()->where("imei = '" . $imei . "'")->one();
        /* @var $check AppDeviceAPI */
        if (isset($check)) {

            $check->token = $token;
            $check->user_id = $user_id;
            $check->is_active = $is_active;

            if ($check->save()) {
                return FApi::getOutputForAPI($check, FConstant::SUCCESS, 'OK', ['code' => 200]);

            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);

            }
        } else {
            $new_device = new AppDeviceAPI();
            $new_device->user_id = $user_id;
            $new_device->is_active = $is_active;
            $new_device->imei = $imei;
            if (is_numeric($type)) {
                if ($type == 1) {
                    $type = AppDeviceAPI::TYPE_ANDROID;
                }
                if ($type == 2) {
                    $type = AppDeviceAPI::TYPE_IOS;
                }
            }
            $new_device->type = $type;
            $new_device->token = $token;
            if ($new_device->save()) {
                $now = FHtml::Now();
                return FApi::getOutputForAPI($new_device, FConstant::SUCCESS, 'OK', [
                    'code' => 200,
                    'time' => $now,
                    'object_type' => AppDeviceAPI::tableName()
                ]);
            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
            }
        }
    }

}
