<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppTokenAPI;
use backend\modules\app\models\AppUserAPI;
use common\components\FConstant;
use common\components\FApi;

class LogoutAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        if ($this->user_id != 0) {
            AppTokenAPI::deleteAll(['user_id' => $this->user_id]);
            $check = AppUserAPI::findOne($this->user_id);
            $check->is_online = FConstant::STATE_INACTIVE;
            $check->save();
            AppTokenAPI::updateAll(['token' => NULL, 'time' => time()], [['user_id' => $this->user_id]]);
        }
        return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
    }
}
