<?php
namespace common\widgets\formfield;

class FormObjectFile extends FormFieldWidget
{
    public function run()
    {
        if (empty($this->view_path))
            $this->view_path = '_form_files';

        return parent::run();

    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>