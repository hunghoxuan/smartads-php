<?php

namespace common\widgets\formfield;

use common\components\FHtml;
use yii\base\Widget;

/**
 * Class FormFieldWidget
 * @package common\widgets\formfield
 */
class FormFieldWidget extends Widget
{
	/**
	 * @var
	 */
	public $items;
	/**
	 * @var array
	 */
	public $items_filter = [];
	/**
	 * @var string
	 */
	public $title = '';
	/**
	 * @var string
	 */
	public $modulePath = '';
	/**
	 * @var string
	 */
	public $view_path = '';
	/**
	 * @var string
	 */
	public $view_type = '';
	/**
	 * @var string
	 */
	public $view_path_form = "";
	/**
	 * @var string
	 */
	public $view_path_grid_view = "";
	/**
	 * @var
	 */
	public $form;
	/**
	 * @var
	 */
	public $model;
	/**
	 * @var
	 */
	public $field_name;
	/**
	 * @var
	 */
	public $field_image;
	/**
	 * @var
	 */
	public $modelMeta;
	/**
	 * @var
	 */
	public $canEdit;
	/**
	 * @var
	 */
	public $moduleKey;
	/**
	 * @var
	 */
	public $object_type;
	/**
	 * @var
	 */
	public $object_id;
	/**
	 * @var
	 */
	public $object_fields;
	/**
	 * @var
	 */
	public $object_attributes;
	/**
	 * @var
	 */
	public $actionLayout;

	/**
	 * @var
	 */
	public $meta_key;
	/**
	 * @var
	 */
	public $id; // control id
	/**
	 * @var bool
	 */
	public $labelSpan = false;
	/**
	 * @var
	 */
	public $relation_type; // could be relation_type field in object_relation table; or foreigned key in child table

	public $can_add_multiple;
	/**
	 * @var string
	 */
	public $form_type = \common\widgets\FActiveForm::TYPE_VERTICAL;
	/**
	 * @var string
	 */
	public $grid_type = FHtml::DISPLAY_TYPE_GRID;

	public $columns;
	public $accept_files;
	public $is_modal;

	public $_attribute = '';
	public $open;

	/**
	 * @return mixed
	 */
	public function getAttribute() {
		return $this->_attribute;
	}

	/**
	 * @param $value
	 */
	public function setAttribute($value) {
		$this->_attribute = $value;
	}

	/**
	 * @return string
	 */
	public function run() {
	    if (!isset($this->canEdit))
	        $this->canEdit = FHtml::isAuthorized('update', $this->object_type);

	    if (empty($this->title))
	        $this->open = false;

		if (!empty($this->view_path)) {
			return $this->renderWidget($this->view_path, array(
				'items'               => $this->items,
				'title'               => $this->title,
				'labelSpan'           => $this->labelSpan,
				'items_filter'        => $this->items_filter,
				'form'                => $this->form,
				'id'                  => $this->id,
				'_attribute'          => $this->_attribute,
				'model'               => $this->model,
				'field_name'          => $this->field_name,
				'field_image'         => $this->field_image,
				'object_type'         => $this->object_type,
				'meta_key'            => $this->meta_key,
				'relation_type'       => $this->relation_type,
				'modelMeta'           => $this->modelMeta,
				'canEdit'             => $this->canEdit,
				'moduleKey'           => $this->moduleKey,
				'modulePath'          => $this->modulePath,
				'form_width'           => $this->view_type,
				'object_fields'       => $this->object_fields,
				'object_attributes'   => $this->object_attributes,
				'form_type'           => $this->form_type,
				'grid_type'           => $this->grid_type,
				'actionLayout'        => $this->actionLayout,
				'view_path_form'      => $this->view_path_form,
				'view_path_grid_view' => $this->view_path_grid_view,
				'can_add_multiple'    => $this->can_add_multiple,
				'columns'             => $this->columns,
				'accept_files'        => $this->accept_files,
				'is_modal'            => $this->is_modal,
                'open'                => $this->open
			));
		}

		return parent::run();
	}

	/**
	 * @param string $view
	 * @param array  $params
	 * @return string
	 */
	public function RenderWidget($view = '', $params = []) {
		$result = '';

		$file = FHtml::findViewFile($view);
		if (is_file($file)) {
			$result .= FHtml::render($view, $params);
		}
		else {
			$result .= $this->render($view, $params);
		}

		return $result;
	}

	/**
	 *
	 */
	protected function prepareData() {

	}
}

?>