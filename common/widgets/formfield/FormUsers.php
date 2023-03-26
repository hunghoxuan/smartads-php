<?php
namespace common\widgets\formfield;

class FormUsers extends FormFieldWidget
{

    public function run()
    {
        $this->object_type = '_users';

        if (empty($this->view_path))
            $this->view_path = '_users';

        return parent::run();
    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>