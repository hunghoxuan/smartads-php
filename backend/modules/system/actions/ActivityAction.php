<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\system\models\ObjectActivityAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FApi;

class ActivityAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $type = FApi::getRequestParam('type', ''); //like/share/favourite/follow
        $object_id = FApi::getRequestParam('object_id', '');
        $object_type = FApi::getRequestParam('object_type', '');

        if (strlen($type) == 0 || strlen($object_id) == 0 || strlen($object_type) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        if ($this->user_id != 0) {
            $check = ObjectActivityAPI::find()->where([
                'type' => $type,
                'object_id' => $object_id,
                'object_type' => $object_type,
                'user_id' => $this->user_id,
                'user_type' => AppUserAPI::tableName(),

            ])->one();

            if ($check) {
                $check->delete();
                System::updateActivity($object_id, $object_type, $type);
                return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK');
            } else {
                $user = AppUserAPI::findOne($this->user_id);
                $now = date('Y-m-d H:i:s', time());
                $new_activity = new ObjectActivityAPI();
                $new_activity->type = $type;
                $new_activity->user_id = $user->id;
                $new_activity->user_type = AppUserAPI::tableName();
                $new_activity->object_id = $object_id;
                $new_activity->object_type = $object_type;
                $new_activity->type = $type;
                $new_activity->created_date = $now;
                if ($new_activity->save()) {
                    System::updateActivity($object_id, $object_type, $type);
                    return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK');
                } else {
                    return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(201), ['code' => 201]);
                }
            }
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(221), ['code' => 221]);
        }
    }
}
