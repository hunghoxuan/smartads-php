<?php
namespace common\widgets;

use common\components\FHtml;
use common\widgets\fheadline\FHeadline;
use yii\base\Widget;
use yii\helpers\StringHelper;
use yii\widgets\InputWidget;

class EmptyWidget extends InputWidget
{
    public $model;
    public $attribute;
    public $form;

    public function run()
    {
        return parent::run();
    }
}

?>