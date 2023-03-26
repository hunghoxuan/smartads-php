<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\smartscreen\models\SmartscreenStationAPI;
use common\components\FApi;
use common\components\FConstant;
use common\actions\BaseApiAction;
use Yii;
use yii\helpers\Json;


class CronStatusDevices extends BaseApiAction
{
    public function run()
    {
		$now = time();
        $time = $now - 120;

        $deviceLostConnect = SmartscreenStationAPI::find()
            ->select(['ime', 'last_update', 'status', 'id'])
            ->where("last_update < $time")
            ->all();
		
        if ($deviceLostConnect) {
            foreach ($deviceLostConnect as $device) {
                $device->status = 0;
                $device->save();

                if ($device->save()) {
                    Yii::$app->redis->executeCommand('PUBLISH', [
                        'channel' => 'notification',
                        'message' => Json::encode(['device' => $device->ime, 'message' => $device->status])
                    ]);
                }
            }
        }
		
		$deviceConnectAgain = SmartscreenStationAPI::find()
			->select(['ime', 'last_update', 'status', 'id'])
            ->andWhere(['between', 'last_update', $time, $now])
            ->andWhere(['status' => 0])
            ->all();
			
		// echo '<pre>';
		// print_r($deviceConnectAgain);die;
			
		if ($deviceConnectAgain) {
            foreach ($deviceConnectAgain as $device) {
                $device->status = 1;
                $device->save();

                if ($device->save()) {
                    Yii::$app->redis->executeCommand('PUBLISH', [
                        'channel' => 'notification',
                        'message' => Json::encode(['device' => $device->ime, 'message' => $device->status])
                    ]);
                }
            }
        }

        return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);

    }
}
