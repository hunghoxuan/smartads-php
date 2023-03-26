<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserSettingAPI;
use common\components\FHtml;
use common\components\Response;

class SettingAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {

        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $notify =  FHtml::getRequestParam('notify', '');
        $notify_favourite = FHtml::getRequestParam('notify_favourite', '');
        $notify_transport = FHtml::getRequestParam('notify_transport', '');
        $notify_food = FHtml::getRequestParam('notify_food', '');
        $notify_labor = FHtml::getRequestParam('notify_labor', '');
        $notify_travel = FHtml::getRequestParam('notify_travel', '');
        $notify_shopping = FHtml::getRequestParam('notify_shopping', '');
        $notify_news = FHtml::getRequestParam('notify_news', '');
        $notify_nearby = FHtml::getRequestParam('notify_nearby', '');

        $check = AppUserSettingAPI::find()->where("user_id = '" . $user_id . "'")->one();
        /* @var $check AppUserSettingAPI */
        // INSERT INTO app_user_setting (user_id, notify, notify_favourite) VALUES(1, 1, 0) ON DUPLICATE KEY UPDATE user_id = 1, notify = 1, notify_favourite =1
        if (isset($check)) {

            if (strlen($notify) != 0)
                $check->notify = $notify;
            if (strlen($notify_favourite) != 0)
                $check->notify_favourite = $notify_favourite;
            if (strlen($notify_transport) != 0)
                $check->notify_transport = $notify_transport;
            if (strlen($notify_food) != 0)
                $check->notify_food = $notify_food;
            if (strlen($notify_labor) != 0)
                $check->notify_labor = $notify_labor;
            if (strlen($notify_travel) != 0)
                $check->notify_travel = $notify_travel;
            if (strlen($notify_shopping) != 0)
                $check->notify_shopping = $notify_shopping;
            if (strlen($notify_news) != 0)
                $check->notify_news = $notify_news;
            if (strlen($notify_nearby) != 0)
                $check->notify_nearby = $notify_nearby;

            if ($check->save()) {
                return Response::getOutputForAPI($check, \Globals::SUCCESS, 'OK', ['code' => 200]);
            } else {
                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);

            }
        } else {
            $check = new AppUserSettingAPI();
            $check->user_id = $user_id;
            $check->notify = is_numeric($notify) ? $notify : 1;
            $check->notify_favourite = is_numeric($notify) ? $notify : 1;
            $check->notify_transport = is_numeric($notify) ? $notify : 1;
            $check->notify_food = is_numeric($notify) ? $notify : 1;
            $check->notify_labor = is_numeric($notify) ? $notify : 1;
            $check->notify_travel = is_numeric($notify) ? $notify : 1;
            $check->notify_shopping = is_numeric($notify) ? $notify : 1;
            $check->notify_news = is_numeric($notify) ? $notify : 1;
            $check->notify_nearby = is_numeric($notify) ? $notify : 1;

            if ($check->save()) {
                return Response::getOutputForAPI($check, \Globals::SUCCESS, 'OK', ['code' => 200]);
            } else {
                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
            }
        }
    }

}
