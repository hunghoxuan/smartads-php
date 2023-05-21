<?php

namespace backend\actions;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;
use backend\models\ToolsCopyAPI;
use common\actions\BaseApiAction;

/**



 * This is the customized model class for table "tools_copy".
 */
class ToolsCopyAction extends BaseApiAction
{
    public $is_secured = false;
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        if (!empty($this->objectid)) {

            $object = ToolsCopyAPI::findOne($this->objectid);

            $out = FHtml::getOutputForAPI($object, $this->objectname, '', 'data', 1);
            $out['code'] = $this->objectid;
            return $out;
        } else {

            $list = ToolsCopyAPI::getDataProvider(Fhtml::mergeRequestParams(['name' => '%' . $this->keyword], $this->paramsArray), $this->orderby, $this->limit, $this->page, false);
            $out = FHtml::getOutputForAPI($list->getModels(), $this->listname, '', 'data', $list->pagination->pageCount);
            $out['code'] = $this->params;
            return $out;
        }
    }
}
