<?php

namespace applications\stechvss\actions;

use backend\models\ObjectAttributes;
use backend\models\User;
use backend\modules\system\models\ObjectHash;
use backend\modules\vss\actions\BaseVssAction;
use backend\modules\vss\models\UserAPI;
use backend\modules\vss\Vss;
use common\actions\BaseApiAction;
use backend\actions\ListObjectAction;
use common\base\BaseAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;
use yii\helpers\Json;

/*
 * This is the customized model class for table "backend\models\MusicArtist".
 */

class UserInfoAction extends BaseAction
{
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        if (empty($this->user_id))
            $user = null;
        else if (is_numeric($this->user_id) && $this->user_id < 10000000) {
            $user = \backend\models\UserAPI::findOne(['id' => $this->user_id], false);
        } else {
            $user = \backend\models\UserAPI::findOne(['code' => $this->user_id], false);
        }

        if (isset($user))
            return FApi::getOutputForAPI($user, FConstant::SUCCESS, 'OK', ['code' => 200]);
        return null;
    }
}
