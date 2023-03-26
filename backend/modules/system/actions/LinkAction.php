<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use backend\modules\system\models\ObjectLinkAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FApi;
use common\components\FHtml;

class LinkAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $this->limit = -1;

        $action = FApi::getRequestParam(['action', 'action_type'], 'list');

        //list required params
        $sort_by = FApi::getRequestParam('sort_by', 'new'); //new
        $page_offset = FApi::getRequestParam('page_offset', $this->offset);
        $page_limit = FApi::getRequestParam('page_limit', $this->limit);

        //object required params
        $id = FApi::getRequestParam('id', '');
        //attributes
        $object_id = FApi::getRequestParam('object_id', '');
        $object_type = FApi::getRequestParam('object_type', '');
        $name = FApi::getRequestParam('name', '');
        $type = FApi::getRequestParam('type', '');
        $link_url = FApi::getRequestParam('link_url', '');

        $user_id = $this->user_id;

        if (strlen($action) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        } else {
            if ($action != "list") {
                if ($action == FConstant::ACTION_CREATE) {
                    if (strlen($name) == 0) {
                        return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
                    }
                } else {
                    if (strlen($id) == 0) {
                        return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
                    }
                }
            }
        }

        $now = FHtml::Now();

        if ($action == 'list') {
            $sort_string = System::getSortString($sort_by);
            $condition = ["is_active" => 1];
            if (strlen($object_id) != 0) {
                $condition['object_id'] = $object_id;
            }

            $total = ObjectLinkAPI::find()->where($condition)->count();
            $reviews = ObjectLinkAPI::find()
                ->where($condition)
                ->orderBy($sort_string)
                ->limit($page_limit)
                ->offset($page_offset)
                ->all();
            return FApi::getOutputForAPI($reviews, FConstant::SUCCESS, 'OK', [
                'code' => 200,
                'total' => $total,
                'page_limit' => $page_limit,
                'page_offset' => $page_limit,
                'time' => $now,
                'object_type' => ObjectLinkAPI::tableName()
            ]);

        } elseif ($action == FConstant::ACTION_CREATE) {

            $object = new ObjectLinkAPI();
            $object->object_id = $object_id;
            $object->name = $name;
            $object->object_type = $object_type;
            $object->type = $type;
            $object->link_url = $link_url;
            $object->is_active = FConstant::STATE_ACTIVE;
            if ($object->save()) {
                return FApi::getOutputForAPI($object, FConstant::SUCCESS, 'OK', [
                    'code' => 200,
                    'time' => $now,
                    'object_type' => ObjectLinkAPI::tableName()
                ]);
            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
            }
        } else {
            $object = ObjectLinkAPI::findOne($id);
            if (!isset($object)) {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(208), ['code' => 208]);
            }
            if ($action == FConstant::ACTION_EDIT) {
                $object->object_id = $object_id;
                $object->name = $name;
                $object->object_type = $object_type;
                $object->type = $type;
                $object->link_url = $link_url;
                if (!$object->save()) {
                    return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
                }
            }
            return FApi::getOutputForAPI($object, FConstant::SUCCESS, 'OK', [
                'code' => 200,
                'time' => $now,
                'object_type' => ObjectLinkAPI::tableName()
            ]);

        }
    }
}
