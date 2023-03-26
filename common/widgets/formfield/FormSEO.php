<?php
namespace common\widgets\formfield;

class FormSEO extends FormFieldWidget
{
    public $relation_type;

    public function run()
    {
        if (empty($this->view_path))
            $this->view_path = '_form_seo';

        return parent::run();
    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>