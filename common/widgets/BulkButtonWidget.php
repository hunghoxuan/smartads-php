<?php
namespace common\widgets;

use yii\base\Widget;

class BulkButtonWidget extends Widget
{
    public $buttons;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $content = $this->buttons;
        return $content;
    }
}

?>
