<?php

namespace backend\actions;

use common\components\FApi;
use common\components\FConstant;
use backend\models\ObjectCategoryAPI;
use common\components\FHtml;

class CategoryAction extends BaseAction
{
    public $is_secured = false;

    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $this->limit = -1;

        $parent_id = FApi::getRequestParam('parent_id', 0);
        $page_offset = FApi::getRequestParam('page_offset', $this->offset);
        $page_limit = FApi::getRequestParam('page_limit', $this->limit);
        $object_type = FApi::getRequestParam('object_type', '');
        
        $condition = ['parent_id' => $parent_id];
        if (strlen($object_type) != 0) {
            $condition['object_type'] = $object_type;
        }

        $all_records = ObjectCategoryAPI::find()
            ->where($condition)
            ->orderBy('id desc')
            ->all();


        $categories = ObjectCategoryAPI::find()
            ->where($condition)
            ->orderBy('sort_order ASC')
            ->limit($page_limit)
            ->offset($page_offset)->all();


        $total = count($all_records);
        $now = FHtml::Now();

        return FApi::getOutputForAPI($categories, FConstant::SUCCESS, 'OK', [
            'code' => 200,
            'total' => $total,
            'page_limit' => $page_limit,
            'page_offset' => $page_limit,
            'time' => $now,
            'object_type' => ObjectCategoryAPI::tableName()
        ]);
    }
}

