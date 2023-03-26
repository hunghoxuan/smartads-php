<?php
namespace common\widgets;

use backend\modules\cms\models\CmsWidgets;
use common\components\FConfig;
use common\components\FFrontend;
use common\components\FHtml;
use common\components\FModel;
use common\widgets\fheadline\FHeadline;
use yii\base\Widget;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;

class ContentWidget extends BaseWidget
{
    public function run()
    {
        self::prepareData();
        return $this->render($this->display_type);
    }

    public function render($view, $params = [], $widgetRender = true)
    {
        return FFrontend::contentWidget($this->object_type, $this->content, $this->item_layout, $this->row_layout, $this->items_count, $this->columns_count);
    }
}

?>