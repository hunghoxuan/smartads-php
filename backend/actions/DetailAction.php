<?php
namespace backend\actions;

use backend\actions\BaseAction;
use common\components\FHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class DetailAction extends BaseAction
{
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $fieldsArray = FHtml::decode($this->fields);

        $out = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object = FHtml::getModel($this->objectname, '', $this->objectid, null, false);
        if (isset($object))
            $object->columnsMode = !empty($fieldsArray) ? $fieldsArray : 'api';

        $out = FHtml::getOutputForAPI($object, $this->objectname,  '', 'data', 1);
        $out['code'] = $this->objectid;
        return $out;
    }
}