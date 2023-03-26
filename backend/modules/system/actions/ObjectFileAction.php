<?php

namespace backend\modules\system\actions;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;
use backend\modules\system\models\ObjectFileAPI;
use common\actions\BaseApiAction;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "object_file".
 */
class ObjectFileAction extends BaseApiAction
{
    public $is_secured = false;
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        if (!empty($this->objectid)) {

            $object = ObjectFileAPI::findOne($this->objectid);

            $out = FHtml::getOutputForAPI($object, $this->objectname, '', 'data', 1);
            $out['code'] = $this->objectid;
            return $out;
        } else {

            $list = ObjectFileAPI::getDataProvider(Fhtml::mergeRequestParams(['name' => '%'.$this->keyword], $this->paramsArray), $this->orderby, $this->limit, $this->page, false);
            $out = FHtml::getOutputForAPI($list->getModels(), $this->listname, '', 'data', $list->pagination->pageCount);
            $out['code'] = $this->params;
            return $out;
        }
    }
}
