<?php

namespace backend\modules\system\actions;

use applications\business\models\BusinessCompanyAPI;
use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\system\models\ObjectMessageAPI;
use backend\modules\system\System;
use common\components\FConstant;
use common\components\FApi;
use common\components\FHtml;

class MessageAction extends BaseAction
{
    public function run()
    {
        $this->isAuthorized();
        if (!empty($this->output)) {
            return $this->output;
        }

        $this->limit = -1;

        $action = FApi::getRequestParam(['action', 'action_type'], 'list'); //list/create/update/delete

        //list required params
        $sort_by = FApi::getRequestParam('sort_by', 'new'); //new
        $page_offset = FApi::getRequestParam('page_offset', 0);
        $page_limit = FApi::getRequestParam('page_limit', $this->limit);

        //object required params
        $id = FApi::getRequestParam('id', '');
        //attributes
        $title = FApi::getRequestParam('title', '');
        $message = FApi::getRequestParam('message', '');
        $company_id = FApi::getRequestParam('company_id', '');

        $user_id = $this->user_id;

        if (strlen($action) == 0) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        } else {
            if ($action != "list") {
                if ($action == FConstant::ACTION_CREATE) {
                    if (strlen($message) == 0) {
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
            if (strlen($company_id) != 0) {
                $condition['object_id'] = $company_id;
            } else {
                $condition['sender_id'] = $user_id;
            }

            $total = ObjectMessageAPI::find()->where($condition)->count();
            $reviews = ObjectMessageAPI::find()
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
                'object_type' => ObjectMessageAPI::tableName()
            ]);

        } elseif ($action == FConstant::ACTION_CREATE) {
            $object = new ObjectMessageAPI();
            $object->object_id = $company_id;
            $object->object_type = BusinessCompanyAPI::tableName();
            $object->title = $title;
            $object->message = $message;
            $object->method = ObjectMessageAPI::METHOD_PUSH;
            $object->type = ObjectMessageAPI::TYPE_NOTIFY;
            $object->send_date = $now;
            $object->sender_id = $user_id;
            $object->sender_type = AppUserAPI::tableName();
            $object->status = ObjectMessageAPI::STATUS_SENT;
            $object->is_active = FConstant::STATE_ACTIVE;
            if ($object->save()) {
                return FApi::getOutputForAPI($object, FConstant::SUCCESS, 'OK', [
                    'code' => 200,
                    'time' => $now,
                    'object_type' => ObjectMessageAPI::tableName()
                ]);
            } else {
                return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
            }
        } elseif ($action == FConstant::ACTION_DELETE) {
            $object = ObjectMessageAPI::findOne($id);
            if (!isset($object)) {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(208), ['code' => 208]);
            } else {
                $object->delete();
                return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', [
                    'code' => 200,
                    'time' => $now,
                    'object_type' => ObjectMessageAPI::tableName()
                ]);
            }
        } else {
            $object = ObjectMessageAPI::findOne($id);
            if (!isset($object)) {
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(208), ['code' => 208]);
            }
            if ($action == FConstant::ACTION_EDIT) {
                $object->title = $title;
                $object->message = $message;
                if (!$object->save()) {
                    return FApi::getOutputForAPI('', FConstant::ERROR, 'FAIL', ['code' => 201]);
                }
            }
            return FApi::getOutputForAPI($object, FConstant::SUCCESS, 'OK', [
                'code' => 200,
                'time' => $now,
                'object_type' => ObjectMessageAPI::tableName()
            ]);

        }
    }
}
