<?php
namespace backend\actions;

use backend\actions\BaseAction;
use common\components\FHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class CountAction extends BaseAction
{
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $out = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $object = FHtml::getModel($this->objectname, '', $this->objectid, null, false);

        if (isset($object)) {
            if (is_string($this->fields))
                $fields = explode(',', $this->fields);
            else if (is_array($this->fields))
                $fields = $this->fields;

            if (!empty($fields)) {
                $object = FHtml::increaseFieldValues($object, $fields, 1);
                $object->save();
            }
        }

        $out = FHtml::getOutputForAPI($object, $this->objectname,  '', 'data', 1);
        $out['code'] = $this->objectid;
        return $out;
    }
}