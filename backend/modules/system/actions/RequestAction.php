<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\system\models\ObjectRequestAPI;
use common\components\FConstant;
use common\components\FApi;
use Yii;

class RequestAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $type = FApi::getRequestParam('type', ''); //vip/moderator
        $object_id = FApi::getRequestParam('object_id', 0);
        $object_type = FApi::getRequestParam('object_type', '');

        if (strlen($type) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        if ($this->user_id != 0) {
            $user = AppUserAPI::findOne($this->user_id);
            $now = date('Y-m-d H:i:s', time());
            $new_request = new ObjectRequestAPI();
            $new_request->name = $user->name;
            $new_request->email = $user->email;
            $new_request->user_id = $user->id;
            $new_request->user_type = AppUserAPI::tableName();
            $new_request->user_role = $this->user_role;
            $new_request->object_id = $object_id;
            $new_request->object_type = $object_type;
            $new_request->type = $type;
            $new_request->is_active = FConstant::STATE_INACTIVE;
            $new_request->created_date = $now;
            if ($new_request->save()) {
                $send = Yii::$app
                    ->mailer
                    ->compose()
                    ->setTextBody('Your have sent a request to ' . Yii::$app->name . ". We will reply you soon.")
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($user->email)
                    ->setSubject('You have sent a request to ' . Yii::$app->name)
                    ->send();
                if ($send) {
                    return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK');
                } else {
                    return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(229), ['code' => 229]);
                }
            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(201), ['code' => 201]);
            }
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(221), ['code' => 221]);
        }
    }
}
