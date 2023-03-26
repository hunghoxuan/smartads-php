<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\FConstant;
use common\components\FApi;
use common\components\FHtml;
use Yii;

class ProfileAction extends BaseAction
{
    public function run()
    {
        $this->model_fields = AppUserAPI::getInstance()->getApiFields();

        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $destination_id = FApi::getRequestParam('user_id', '');
        $user_id = $this->user_id;

        /* @var $check AppUserAPI */

        if (strlen($destination_id) == 0) {
            $check = AppUserAPI::find()->where(['id' => $user_id])->one();
        } else {
            $check = AppUserAPI::find()->where(['id' => $destination_id])->one();
        }

        Yii::$app->response->statusCode = 200;
        $now = FHtml::Now();

        if (isset($check)) {
            $data = $check;
            return FApi::getOutputForAPI($data, FConstant::SUCCESS, 'OK', [
                'code' => 200,
                'time' => $now,
                'object_type' => AppUserAPI::tableName()
            ]);
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(221), ['code' => 221]);
        }
    }

}
