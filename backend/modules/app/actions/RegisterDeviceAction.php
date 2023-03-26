<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use common\components\FApi;
use common\components\FConstant;

class RegisterDeviceAction extends BaseAction
{
    public function run()
    {
        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';

        if (strlen($ime) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);

        }

        $check = SmartscreenStationAPI::find()
            ->where(['ime' => $ime])
            ->one();

        if ($check) {
            return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
        }

        $new_device = new SmartscreenStationAPI();
        $new_device->ime = $ime;
        $new_device->status = FConstant::STATE_ACTIVE;

        if ($new_device->save()) {
            $result = FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
        } else {
            $result = FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
        }

        return $result;
    }

}
