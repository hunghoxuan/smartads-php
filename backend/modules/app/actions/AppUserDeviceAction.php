<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\actions\BrowseAction;
use common\components\FHtml;
use yii\helpers\Json;


/**
 *
 ***
 * This is the customized model class for table "backend\modules\app\models\AppUserDevice".
 */
class AppUserDeviceAction extends BaseAction
{
    public function run()
    {
        $this->listname = 'app_user_device';
        $this->objectname = 'app_user_device';
        $paramArray = FHtml::decode($this->params);
        $listArray = FHtml::decode($this->listname);

        if (!empty($this->objectid)) {
            $object = FHtml::getModel($this->objectname, '', $this->objectid, null, false);
            if (isset($object)) {
                $object->columnsMode = !empty($fieldsArray) ? $fieldsArray : 'api';
                $object->prepareCustomFields();
            }

            $out = FHtml::getOutputForAPI($object, $this->objectname, '', 'data', 1);
            $out['code'] = $this->objectid;
            return $out;
        } else {
            $list = FHtml::getModelsList($this->listname, $paramArray, $this->orderby, $this->limit, $this->page, true);
            $out = FHtml::getOutputForAPI($list->getModels(), $this->listname, '', 'data', $list->pagination->pageCount);
            $out['code'] = $this->params;
            return $out;
        }
    }
}
