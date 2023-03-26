<?php

namespace backend\actions;

use backend\modules\app\models\AppDeviceAPI;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;
use yii\helpers\ArrayHelper;



class PushNotificationAction extends BaseAction
{
    public function run()
    {
        $title = FHtml::getRequestParam('title', '');
        $message = FHtml::getRequestParam('message', '');
        $data = FHtml::getRequestParam('data', '');

        $condition = ['is_active' => FConstant::STATE_ACTIVE];

        $android_condition =  ['AND', $condition, ['OR', ['type' => 1], ['type' => 'android']]];
        $ios_condition =  ['AND', $condition, ['OR', ['type' => 2], ['type' => 'ios']]];

        $all_android_devices = AppDeviceAPI::find()->select('token')->where($android_condition)->all();
        $android_devices = array_column(ArrayHelper::toArray($all_android_devices), 'token');

        $all_ios_devices = AppDeviceAPI::find()->select('token')->where($ios_condition)->all();
        $ios_devices = array_column(ArrayHelper::toArray($all_ios_devices), 'token');

        $check = json_decode($data);
        $additional_data = ['title' => $title];
        if (json_last_error() == JSON_ERROR_NONE) {
            if (is_array($check)) {
                $additional_data = array_merge($additional_data, $check);
            }
        }

        if (!empty($android_devices)) {
            try {
                FApi::pushAndroid($android_devices, $message, $additional_data);
            } catch (\Exception $e) {
                return $e;
            }
        }

        if (!empty($i_devices)) {
            try {
                FApi::pushIosFcm($ios_devices, $message, $additional_data);
            } catch (\Exception $e) {
                return $e;
            }
        }
    }
}
