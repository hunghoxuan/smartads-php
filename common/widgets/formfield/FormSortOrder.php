<?php
namespace common\widgets\formfield;

class FormSortOrder extends FormFieldWidget
{

    public function run()
    {
        if (empty($this->view_path))
            $this->view_path = '_form_sort_order';

        return parent::run();
    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>