<?php
namespace common\widgets\fheadline;

use common\components\FHtml;
use common\widgets\BaseWidget;

class FHeadline extends BaseWidget
{
    public function run()
    {
        $this->is_widget = false;
        $this->show_headline = false;

        self::prepareData();
        if (empty($this->title_display_type))
            $this->title_display_type = FHtml::HEADLINE_TYPE_CENTER_V2;

        if (empty($this->color))
            $this->color = FHtml::currentApplicationMainColor();

        if (empty($this->display_type))
            $this->display_type = 'headline';

        return $this->render($this->display_type,
            array(
                'title' => $this->title, 'overview' => $this->overview,
                'color' => $this->color, 'style' => $this->style,
                'title_display_type' => $this->title_display_type,
                'margin' => $this->margin,
            )
        );
    }
}

?>