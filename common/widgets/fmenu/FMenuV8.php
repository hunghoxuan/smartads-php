<?php
namespace common\widgets\fmenu;

class FMenuV8 extends FMenu
{
    public function run()
    {
        $this::prepareData();

        return parent::run();
    }
}

?>