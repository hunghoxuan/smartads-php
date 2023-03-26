<?php
namespace common\widgets\fmenu;

use common\widgets\BaseWidget;

class FMenu extends BaseWidget
{
    public $data;

    public function run()
    {
        $this::prepareData();

        return $this->renderWidget('menu', array(
                'data' => $this->data,
            )
        );
    }
}

?>