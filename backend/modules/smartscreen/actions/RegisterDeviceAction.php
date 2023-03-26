<?php
namespace backend\modules\smartscreen\actions;

use backend\actions\BaseAction;
use backend\modules\smartscreen\models\SmartscreenStation;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;

class RegisterDeviceAction extends BaseAction
{
    public function run()
    {
        $ime = FHtml::getRequestParam(['ime', 'mac_address']);
        $mac_address = FHtml::getRequestParam(['mac_address']);

        $name =  FHtml::getRequestParam(['name', 'device_name']);
        $description = FHtml::getRequestParam(['description', 'device_description']);
        $branch_id = FHtml::getRequestParam(['branch_id', 'branch', 'branchId']);
        $screen_name = FHtml::getRequestParam(['device_vote_id', 'ScreenName', 'screen_name']);
        $activity = FHtml::getRequestParam(['last_activity', 'activity']);

        if (strlen($ime) == 0) {
            $result = FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
            $result['current_time'] = time();

            return $result;
        }

        $check = SmartscreenStationAPI::find()
            ->where(['ime' => $ime])
            ->one();

        $check2 = SmartscreenStationAPI::find()
            ->where(['name' => $name])
            ->one();

        if ($check) {
            if (Smartscreen::settingSaveDeviceNameWhenRegistered() && (isset($check2) && $check->id != $check2->id)) {
                $name = $name . '-' . $check->id;
            }

            $needRefresh = false;
            if ($check->name != $name && !empty($name)) {
                $check->name = $name;
                $needRefresh = true;
            }

            if (!empty($description) && $check->description != $description) {
                $check->description = $description;
                $needRefresh = true;
            }

            if (empty($check->branch_id))
                $check->branch_id = $branch_id;

            $check->ScreenName = $screen_name;
            $check->MACAddress = $mac_address;

            $check->last_update = time();
            $check->last_activity = $activity;

            $a = $check->save();
            Smartscreen::clearCacheKey(SmartscreenStation::tableName());

            $check = SmartscreenStation::findOneCached($ime);

            if (empty($check->errors) && $check->status)
                $result = FApi::getOutputForAPI($check, FConstant::SUCCESS, 'OK', ['code' => 200]);
            else {
                $result = FApi::getOutputForAPI($check, FConstant::ERROR, (empty($check->status) ? FHtml::t('message', 'Item is disabled') : '') . '; ' . FHtml::addError($check->errors), ['code' => 201]);
            }
        } else {
            if (isset($check2)) {
                $name = $name . '_' . date('YmdHis') . rand(1,100);
            }
            $default_channel = Smartscreen::getDefaultChannel();
            $new_device = new SmartscreenStationAPI();
            $new_device->ime = $ime;
            $new_device->description = $description;
            $new_device->name = $name;
            $new_device->last_update = time();
            $new_device->branch_id = $branch_id;
            $new_device->ScreenName = $screen_name;
            $new_device->MACAddress = $mac_address;
            $new_device->channel_id = isset($default_channel) ? $default_channel->id : null;
            $new_device->last_activity = $activity;

            $new_device->status = Smartscreen::getDefaultDeviceStatus() ? 1 : 0;
            $new_device->save();
            $check = $new_device;

            if (empty($new_device->errors) && $new_device->status) {
                $result = FApi::getOutputForAPI($new_device, FConstant::SUCCESS, 'OK', ['code' => 200]);
            } else {
                $result = FApi::getOutputForAPI($new_device, FConstant::ERROR, (empty($new_device->status) ? FHtml::t('message', 'Item is disabled') : '') . '; ' . FHtml::addError($new_device->errors), ['code' => 201]);
            }
        }

        $result['current_time'] = time();
        $result['license'] = isset($check) ? $check->LicenseKey : "";
        $settings = Smartscreen::getSettings($check);
        $result['settings'] = $settings;

        return $result;
    }

}
