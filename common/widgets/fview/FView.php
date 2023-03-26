<?php
namespace common\widgets\fview;

use common\components\FHtml;
use common\widgets\BaseWidget;
use yii\helpers\BaseInflector;

class FView extends BaseWidget
{
    public function run()
    {
        $this::prepareData();

        return $this->RenderWidget($this->display_type, array(
                'items' => $this->items, // related items
                'model' => $this->model, // detail model
                'title' => $this->title,
                'overview' => $this->overview,
                'title_display_type' => $this->title_display_type,
                'width_css' => $this->width_css,
                'background_css' => $this->background_css,
                'style' => $this->style,
                'alignment' => $this->alignment,
                'columns_count' => $this->columns_count,
                'items_count' => $this->items_count,
                'color' => $this->color,
                'image_width' => $this->image_width,
                'image_height' => $this->image_height,
                'field_title' => $this->field_title,
                'field_overview' => $this->field_overview,
                'item_layout' => $this->item_layout,
                'label_viewmore' => $this->label_viewmore,
                'image_folder' => $this->image_folder,
                'link_url' => $this->link_url,
                'show_viewmore' => $this->show_viewmore,
                'is_preview' => $this->is_preview,
                'viewmore_url' => $this->viewmore_url,
                'show_border' => $this->show_border,
                'items_filter' => $this->items_filter,
                'object_type' => $this->object_type
            )
        );
    }

    protected function prepareData()
    {
        if ($this->alignment = 'pull-left') {
            $this->alignment = '';
        }

        if (empty($this->display_type))
            $this->display_type = 'fblog';

        if (empty($this->link_url) && !empty($this->object_type))
            $this->link_url = '/' . $this->object_type . '/list';

        if (empty($this->color))
            $this->color = FHtml::currentApplicationMainColor();

        if (empty($this->object_type))
            $this->object_type = 'product';

        if (empty($this->image_folder))
            $this->image_folder = BaseInflector::camel2id($this->object_type);

        if (!isset($this->model)) {
            $model = FHtml::getModel($this->object_type, FHtml::getRequestParam('type'), FHtml::getRequestParam('id'));
            $this->model = FHtml::getModel($this->object_type);
        }

        if (empty($this->admin_url))
            $this->admin_url = self::buildAdminURL();

        parent::prepareData(); // TODO: Change the autogenerated stub
    }

    public function buildAdminURL()
    {
        $url = '';
        $id = FHtml::getRequestParam(['id', 'product_id']);

        if (!empty($this->admin_url))
            $url = $this->admin_url;
        if (!empty($this->object_type)) {

            $action = empty($id) ? '/index' : '/update';
            $params = empty($id) ? [] : ['id' => $id];
            $url = FHtml::createUrl(str_replace('_', '-', $this->object_type) . $action, $params, ['frontend' => 'backend']);
        }
        return $url;
    }
}

?>