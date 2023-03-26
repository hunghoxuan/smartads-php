<?php
namespace backend\actions;

use backend\actions\BaseAction;
use common\components\FHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class SecuredAction extends BaseAction
{
    public function init()
    {
        $this->is_secured = true;
        parent::init(); // TODO: Change the autogenerated stub
    }
}