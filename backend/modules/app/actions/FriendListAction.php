<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserFriendAPI;
use common\components\FHtml;
use common\components\Response;

class FriendListAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $friends = AppUserFriendAPI::find()->where("user_id = $user_id")->all();
        return Response::getOutputForAPI($friends, \Globals::SUCCESS, 'OK', ['code' => 200]);
    }
}
