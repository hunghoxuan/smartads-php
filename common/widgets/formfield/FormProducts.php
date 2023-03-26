<?php
namespace common\widgets\formfield;

class FormProducts extends FormFieldWidget
{

    public function run()
    {
        $this->object_type = '_products';

        if (empty($this->view_path))
            $this->view_path = '_products';

        return parent::run();
    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>