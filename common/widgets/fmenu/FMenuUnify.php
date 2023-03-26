<?php
namespace common\widgets\fmenu;

class FMenuUnify extends FMenu
{
    public function run()
    {
        $this::prepareData();

        return $this->renderWidget('menu-unify', array(
                'data' => $this->data,
            )
        );
    }
}

?>