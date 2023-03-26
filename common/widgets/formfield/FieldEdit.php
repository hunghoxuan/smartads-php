<?php
namespace common\widgets\formfield;

class FieldEdit extends FormFieldWidget
{
    public $editor;
    public $lookup;
    public $params;
    public $field;
    public $attribute;

    public function run()
    {
        if (empty($this->field) && !empty($this->attribute))
            $this->field = $this->attribute;

        if (empty($this->view_path))
            $this->view_path = '_field_edit';

        return $this->renderWidget($this->view_path, array(
                'items' => $this->items,
                'title' => $this->title,
                'items_filter' => $this->items_filter,
                'form' => $this->form,
                'model' => $this->model,
                'field' => $this->field,
                'editor' => $this->editor,
                'params' => $this->params,
                'lookup' => $this->lookup,
                'modelMeta' => $this->modelMeta, 'canEdit' => $this->canEdit, 'moduleKey' => $this->moduleKey, 'modulePath' => $this->modulePath
            )
        );
    }

    protected function prepareData()
    {

    }
}

?>