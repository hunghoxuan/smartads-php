<?php
namespace common\widgets\fcounter;

use backend\modules\cms\models\CmsStatistics;
use common\components\FHtml;
use common\widgets\BaseWidget;
use yii\helpers\ArrayHelper;

class FCounter extends BaseWidget
{
    public function run()
    {
        self::prepareData();

        return $this->RenderWidget('counter1.php',
            ['items' => $this->items,
                'color' => $this->color,
                'field_title' => $this->field_title,
                'field_overview' => $this->field_overview,
                'background_css' => $this->background_css,
                'width_css' => $this->width_css,
                'items_count' => $this->items_count]);
    }

    protected function prepareData()
    {
        if (empty($this->object_type))
            $this->object_type = 'cms_statistics';

        if (empty($this->items_count))
            $this->items_count = 4;

        if (empty($this->display_type))
            $this->display_type = FHtml::generateRandomInArray(['1']);

        if (empty($this->width_css)) {
            $this->width_css = FHtml::WIDGET_WIDTH_FULL;
        }

        if (empty($this->background_css)) {
            if ($this->display_type == '1') {
                $this->background_css = 'parallax-counter-v1 parallaxBg';
            } else if ($this->display_type == '3') {
                $this->background_css = 'parallax-counter-v3 parallaxBg';
            } else if ($this->display_type == '2') {
                $this->background_css = 'parallax-counter-v2 parallaxBg';
            } else if ($this->display_type == '4') {
                $this->background_css = 'parallax-counter-v4 parallaxBg';
            } else {
                $this->background_css = 'parallax-counter parallaxBg';
            }
        }

        if (!isset($this->items))
            $this->items = CmsStatistics::find()->where(ArrayHelper::merge($this->items_filter, ['is_active' => 1, 'is_top' => 1]))->limit($this->items_count)->orderBy('sort_order ASC, name ASC')->all();


        parent::prepareData(); // TODO: Change the autogenerated stub
    }
}

?>