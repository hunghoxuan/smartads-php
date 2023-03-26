<?php
namespace backend\actions;

use backend\actions\BaseAction;
use common\components\FHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class CreateAction extends SecuredAction
{
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $object = FHtml::createModel($this->objectname);
        if (isset($object)) {
            FHtml::setFieldValues($object, $this->paramsArray);

            if (method_exists($object, 'save')) {
                $object->save();
                FHtml::prepareDefaultValues($object);

                $out = FHtml::getOutputForAPI($object, $this->objectname,  '', 'data', 1);
                $out['code'] = $this->objectid;
            } else {
                $out = FHtml::getOutputForAPI($object, $this->objectname, 'Object ' . $this->objectname . ' does not support function Save() ' . $this->objectid, 'data', 1);
                $out['code'] = $this->objectid;
            }
        } else {
            $out = FHtml::getOutputForAPI($object, $this->objectname, 'Could create object ' . $this->objectname, 'data', 1);
            $out['code'] = $this->objectid;
        }
        return $out;
    }
}