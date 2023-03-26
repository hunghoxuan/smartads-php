<?php
namespace common\widgets\formfield;

class FormWorkFlow extends FormFieldWidget
{
    public function run()
    {
        $this->object_type = 'workflow_comments';

        if (empty($this->view_path))
            $this->view_path = '_form_workflow_comments';

        return parent::run();
    }

    protected function prepareData()
    {
        parent::prepareData();
    }
}

?>