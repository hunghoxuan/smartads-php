<?php
namespace common\widgets\formfield;

class FormObjectAttributes extends FormFieldWidget
{

    public function run()
    {
        if (empty($this->view_path))
            $this->view_path = '_form_attributes';

        return parent::run();
    }

    protected function prepareData()
    {

    }
}

?>