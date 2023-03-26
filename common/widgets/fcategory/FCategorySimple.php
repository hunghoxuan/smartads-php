<?php
namespace common\widgets\fcategory;

use common\components\FHtml;
use common\widgets\BaseWidget;

class FCategorySimple extends FCategory
{

    protected function prepareData()
    {
        parent::prepareData();
        $this->admin_url = false;
    }
}

?>