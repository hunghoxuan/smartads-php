<?php
namespace common\widgets\fslider;

use common\components\FHtml;

class FSliderMaster extends FSlider
{
    public function run()
    {
        if (empty($this->image_folder))
            $this->image_folder = SLIDER_DIR;

        if (!isset($this->items))
            $this->items = FHtml::getModels('cms_slide');

        $this::prepareData();

        return parent::run();
    }
}

?>