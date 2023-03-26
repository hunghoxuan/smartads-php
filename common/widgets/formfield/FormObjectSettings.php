<?php
namespace common\widgets\formfield;

class FormObjectSettings extends FormFieldWidget
{

    public function run()
    {
        if (empty($this->view_path))
            $this->view_path = '_form_settings';

        return parent::run();
    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>