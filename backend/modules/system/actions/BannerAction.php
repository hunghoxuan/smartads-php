<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use backend\modules\system\models\ObjectBannerAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FApi;
use common\components\FHtml;

class BannerAction extends BaseAction
{
    public function run()
    {
        $this->limit = -1;

        $action = FApi::getRequestParam(['action', 'action_type'], 'list');

        //list required params
        $sort_field = FApi::getRequestParam('sort_field', 'sort_order'); //sort_order
        $sort_order = FApi::getRequestParam('sort_order', 'asc');//asc/desc
        $filter_by = FApi::getRequestParam('filter_by', '');
        $page_offset = FApi::getRequestParam('page_offset', $this->offset);
        $page_limit = FApi::getRequestParam('page_limit', $this->limit);
        $keyword = FApi::getRequestParam('keyword', '');

        if (strlen($action) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        } else {
            if ($action != "list") {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(206), ['code' => 206]);
            }
        }
        $now = FHtml::Now();
        if ($action == 'list') {
            $sort_string = System::getSortString($sort_field, $sort_order);
            $condition = ['is_active' => 1];
            $and_on_condition = [];
            if (strlen($keyword) != 0) {
                $and_on_condition[] = ['like', 'name', $keyword];
            }
            if (strlen($filter_by) != 0) {
                $and_on_condition[] = ['type' => $filter_by];
            }
            if (!empty($and_on_condition)) {
                $condition = array_merge(['AND', $condition], $and_on_condition);
            }
            $total = ObjectBannerAPI::find()->where($condition)->count();
            $objects = ObjectBannerAPI::find()
                ->where($condition)
                ->orderBy($sort_string)
                ->limit($page_limit)
                ->offset($page_offset)
                ->all();
            return FApi::getOutputForAPI($objects, FConstant::SUCCESS, 'OK', [
                'code' => 200,
                'total' => $total,
                'page_limit' => $page_limit,
                'page_offset' => $page_limit,
                'time' => $now,
                'object_type' => ObjectBannerAPI::tableName()
            ]);
        } else {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(206), ['code' => 206]);
        }
    }
}

