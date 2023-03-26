<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserFriendAPI;
use common\components\FHtml;
use common\components\Response;

class FriendAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $friend_id = FHtml::getRequestParam('friend_id', '');
        $action = FHtml::getRequestParam('action', ''); //add/remove
        if (
            strlen($friend_id) == 0 ||
            strlen($action) == 0
        ) {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code' => 202]);
        }

        $friend = AppUserFriendAPI::find()->where("user_id = $user_id AND friend_id = $friend_id")->one();

        if ($action == 'add') {
            if(!isset($friend)){
                $new_friend =  new AppUserFriendAPI();
                $new_friend->user_id = $user_id;
                $new_friend->friend_id = $friend_id;
                $new_friend->save();
            }
        }
        if ($action == 'remove') {
            if(isset($friend)){
                AppUserFriendAPI::deleteAll("user_id = $user_id AND friend_id = $friend_id");
            }
        }
        return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code' => 200]);
    }
}
