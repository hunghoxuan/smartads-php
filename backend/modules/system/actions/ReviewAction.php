<?php

namespace backend\modules\system\actions;

use applications\business\Business;
use backend\actions\BaseAction;
use backend\modules\system\models\ObjectReviewAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FHtml;
use common\components\FApi;
use Yii;

class ReviewAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $object_id = FHtml::getRequestParam('object_id', '');
        $object_type = FHtml::getRequestParam('object_type', '');
        $name = FHtml::getRequestParam('name', '');
        $email = FHtml::getRequestParam('email', '');
        $title = FHtml::getRequestParam('title', '');
        $content = FHtml::getRequestParam('content', '');
        $rate = FHtml::getRequestParam('rate', '');

        $user_id = $this->user_id;


        if (strlen($object_id) == 0
            || strlen($object_type) == 0
            || (strlen($content) == 0 && strlen($rate) == 0)
        ) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        Yii::$app->response->statusCode = 200;


        $check = ObjectReviewAPI::find()->where([
            'user_id' => $user_id,
            'object_id' => $object_id,
            'object_type' => $object_type,
            'user_type' => 'app_user',
        ])->one();

        /* @var ObjectReviewAPI $check */

        $now = date('Y-m-d H:i:s', time());

        if (isset($check)) {
            $check->rate = $rate;
            $check->title = $title;
            $check->content = $content;
            $check->modified_date = $now;
            if ($check->save()) {
                System::updateRating($object_id, $object_type);
                $object = Business::getObject($object_id, $object_type);
                return FApi::getOutputForAPI($object, FConstant::SUCCESS, 'OK', ['code' => 200]);
            } else {
                $errors = $check->getErrors();
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMessage($errors), ['code' => 203]);

            }

        } else {
            $rating = new ObjectReviewAPI();
            $rating->object_id = $object_id;
            $rating->object_type = $object_type;
            $rating->rate = $rate;
            $rating->title = $title;
            $rating->content = $content;
            $rating->created_date = $now;
            $rating->user_id = $user_id;
            $rating->user_type = 'app_user';
            $rating->user_role = $this->user_role;
            $rating->like_count = 0;
            $rating->comment_count = 0;
            $rating->share_count = 0;
            $rating->dislike_count = 0;
            $rating->name = $name;
            $rating->email = $email;
            $rating->type = '';
            $rating->is_active = FConstant::STATE_INACTIVE;
            if ($rating->save()) {
                System::updateRating($object_id, $object_type);
                $object = Business::getObject($object_id, $object_type);
                return FApi::getOutputForAPI($object, FConstant::SUCCESS, 'OK', ['code' => 200]);
            } else {
                $errors = $rating->getErrors();
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMessage($errors), ['code' => 203]);
            }
        }
    }
}
