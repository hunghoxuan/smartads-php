<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use backend\modules\system\models\ObjectCommentAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FApi;
use Yii;

class CommentAction extends BaseAction
{
    public $is_secured = false;

    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $object_id = FApi::getRequestParam('object_id', '');
        $object_type = FApi::getRequestParam('object_type', '');
        $name = FApi::getRequestParam('name', '');
        $email = FApi::getRequestParam('email', '');
        $title = FApi::getRequestParam('title', '');
        $content = FApi::getRequestParam('content', '');
        $type = FApi::getRequestParam('type', ''); // comment / reply
        $parent_id = FApi::getRequestParam('parent_id', '');

        $user_id = $this->user_id;

        if (strlen($object_id) == 0
            || strlen($object_type) == 0
            || strlen($content) == 0
        ) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        if (!in_array($type, ['comment', 'reply'])) {
            return FApi::getOutputForAPI('', FConstant::SUCCESS, FApi::getErrorMsg(203), ['code' => 203]);
        }

        Yii::$app->response->statusCode = 200;
        $now = date('Y-m-d H:i:s', time());

        $rating = new ObjectCommentAPI();
        $rating->object_id = $object_id;
        $rating->object_type = $object_type;
        $rating->parent_id = $parent_id;
        $rating->title = $title;
        $rating->content = $content;
        $rating->created_date = $now;
        $rating->user_id = $user_id;
        $rating->user_type = 'app_user';
        $rating->user_role = $this->user_role;
        $rating->like_count = 0;
        $rating->reply_count = 0;
        $rating->share_count = 0;
        $rating->dislike_count = 0;
        $rating->name = $name;
        $rating->email = $email;
        $rating->type = $type;
        $rating->is_active = FConstant::STATE_INACTIVE;
        if ($rating->save()) {
            if ($type == 'comment') {
                System::updateComment($object_id, $object_type);
            } else {
                System::updateReply($parent_id, ObjectCommentAPI::tableName());
            }
            return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
        } else {
            $errors = $rating->getErrors();
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMessage($errors), ['code' => 203]);
        }
    }
}
