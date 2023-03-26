<?php
namespace common\widgets\formfield;

use common\components\FHtml;

class FormObjectSettingSortOrder extends FormSortOrder
{
    public $meta_key;

    public function run()
    {
        if (!isset($this->items))
            $this->items = FHtml::getObjectSettings($this->object_type, $this->field_name);

        $this->field_name = 'value';
        $this->object_type = FHtml::TABLE_OBJECT_SETTING;

        return parent::run();
    }

    protected function prepareData()
    {

        parent::prepareData();
    }
}

?>