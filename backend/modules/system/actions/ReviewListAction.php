<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use backend\modules\system\models\ObjectReviewAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FApi;
use Yii;

class ReviewListAction extends BaseAction
{
    public $fields_required = true;

    public function run()
    {
        $this->model_fields = ObjectReviewAPI::getInstance()->getApiFields();

        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $this->limit = -1;

        $object_id = FApi::getRequestParam('object_id', '');
        $object_type = FApi::getRequestParam('object_type', '');
        $sort_by = FApi::getRequestParam('sort_by', ''); //new/mine/like/reply/dislike
        $page_offset = FApi::getRequestParam('page_offset', $this->offset);
        $page_limit = FApi::getRequestParam('page_limit', $this->limit);

        if (strlen($object_id) == 0
            || strlen($object_type) == 0
        ) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        $user_id = $this->user_id;

        if ($sort_by == 'mine') {
            $sort_string = "user_id = $user_id";
        } else {
            $sort_string = System::getSortString($sort_by);
        }

        Yii::$app->response->statusCode = 200;

        /* @var ObjectReviewAPI $check */

        $now = date('Y-m-d H:i:s', time());

        $condition = ['object_id' => $object_id, 'object_type' => $object_type];

        $all = ObjectReviewAPI::find()->where($condition)->all();

        $total = count($all);

        $reviews = ObjectReviewAPI::find()
            ->where($condition)
            ->orderBy($sort_string)
            ->limit($page_limit)
            ->offset($page_offset)
            ->all();

        return FApi::getOutputForAPI($reviews, FConstant::SUCCESS, 'OK', [
            'code' => 200,
            'total' => $total,
            'page_limit' => $page_limit,
            'page_offset' => $page_offset,
            'time' => $now,
            'object_type' => ObjectReviewAPI::tableName()
        ]);

    }
}
