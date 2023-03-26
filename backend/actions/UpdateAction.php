<?php
namespace backend\actions;

use backend\actions\BaseAction;
use common\components\FHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class UpdateAction extends BaseAction
{
    public function run()
    {
        $object = FHtml::getModel($this->objectname, '', $this->objectid, null, false);
        if (isset($object)) {
            FHtml::setFieldValues($object, $this->paramsArray);

            if (method_exists($object, 'save')) {
                $object->save();

                $out = FHtml::getOutputForAPI($object, $this->objectname, '', 'data', 1);
                $out['code'] = $this->objectid;
            } else {
                $out = FHtml::getOutputForAPI($object, $this->objectname, 'Object ' . $this->objectname . ' does not support function Save() ' . $this->objectid, 'data', 1);
                $out['code'] = $this->objectid;
            }
        } else {
            $out = FHtml::getOutputForAPI($object, $this->objectname, 'Could not find ' . $this->objectname . ' with id = ' . $this->objectid, 'data', 1);
            $out['code'] = $this->objectid;
        }
        return $out;
    }
}