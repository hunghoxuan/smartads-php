<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\FApi;
use common\components\FConstant;

class ChangePasswordAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $user_id = $this->user_id;
        $current_password = FApi::getRequestParam('current_password', '');
        $new_password = FApi::getRequestParam('new_password', '');

        $check = AppUserAPI::findOne($user_id);
        /* @var $check AppUserAPI*/
        if (isset($check)) {
            if (isset($check->password) && strlen($check->password) != 0) {
                if (!$check->validatePassword($current_password)) {
                    return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(225), ['code' => 225]);
                }
                if ($check->validatePassword($new_password)) {
                    return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(234), ['code' => 234]);
                }
            }
            $check->setPassword($new_password);
            $check->generateAuthKey();

            if ($check->save()) {
                return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
            }
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(221), ['code' => 221]);
        }
    }
}
