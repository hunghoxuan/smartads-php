<?php

use common\components\FHtml;
use common\components\Helper;
use common\widgets\formfield\FormFieldWidget;
use unclead\multipleinput\MultipleInput;
use yii\widgets\Pjax;

?>

<?php

if (empty($object_type) || !isset($model) || !FHtml::isTableExisted($object_type)) {
	echo FHtml::showErrorMessage(FHtml::t('common', "Object Type [$object_type] is not valid or not found"));

	return;
}

if (in_array(FHtml::currentAction(), ['view'])) {
	$canEdit = false;
}

$relation_type = isset($relation_type) ? $relation_type : FHtml::RELATION_MANY_MANY;

$relation_field = isset($relation_type) && !empty($relation_type) ? $relation_type : 'object_id';
$model_table    = FHtml::getTableName($model);
$related_model  = FHtml::createModel($object_type);

if (!isset($related_model) || !isset($model) || $model->isNewRecord || empty($model->id)) {
	echo FHtml::showAlert(FHtml::t('message', 'This feature is disabled in Create mode'));

	return;
}

$related_condition = $relation_field == 'object_id' ? ['object_id' => $model->id, 'object_type' => FHtml::getTableName($model)] : [$relation_field => $model->id];
$data              = isset($data) ? $data : FHtml::getDataProvider($object_type, $related_condition);

if (!isset($_attribute) || empty($_attribute)) {
	$_attribute = \yii\helpers\BaseInflector::camelize(FHtml::getTableName($model)) . \yii\helpers\BaseInflector::camelize($object_type);
}


$grid_id        = isset($grid_id) ? $grid_id : 'crud-datatable' . $_attribute . $relation_type;
$pjax_container = isset($pjax_container) ? $pjax_container : $grid_id . '-pjax';
$form_id        = str_replace('_', '-', $object_type) . $pjax_container;

$model_camelized_name = \yii\helpers\BaseInflector::camelize($model_table);
$field_camelized_name = \yii\helpers\BaseInflector::camelize($_attribute);

$object_fields = !empty($object_fields) ? $object_fields : FHtml::getModelPreviewFields($related_model);

$object_attributes = !empty($object_attributes) ? $object_attributes : [];

//set setting for form
$form_type = !empty($form_type) ? $form_type : \common\widgets\FActiveForm::TYPE_VERTICAL;
$is_modal  = isset($is_modal) ? $is_modal : true;
$is_modal  = false;

$create_url = isset($create_url) ? $create_url : FHtml::createModelUrl($object_type, 'create', [
	empty($relation_type) ? null : $relation_type => $model->id,
	'object_type'                                 => $model_table,
	'object_id'                                   => $model->id,
	'pjax_container'                              => "$grid_id-pjax",
	'layout'                                      => 'no',
	'return_url'                                  => FHtml::currentUrl()
]);

$object_type_module = FHtml::getModelModule($object_type);

if (!$is_modal && $canEdit && (empty($form_type) || $form_type == \common\widgets\FActiveForm::TYPE_VERTICAL) && empty($create_url)) {
	$form_width     = '4';
	$list_width     = '8';
	$form_type      = \kartik\form\ActiveForm::TYPE_VERTICAL;
	$form_css_style = '';
}
else {
	$form_width     = '12';
	$list_width     = '12';
	$form_type      = \kartik\form\ActiveForm::TYPE_HORIZONTAL;
	$form_css_style = 'display: none';
}

$grid_type = isset($grid_type) ? $grid_type : FHtml::DISPLAY_TYPE_GRID;
if ($grid_type == FHtml::DISPLAY_TYPE_GRID) {
	$show_header     = true;
	$object_columns1 = isset($columns) ? $columns : FHtml::getModelGridColumns($model, $object_fields);
}
else {
	$show_header     = false;
	$object_columns1 = [
		[
			'class'     => 'kartik\grid\DataColumn',
			'label'     => FHtml::t('common', 'Name'),
			'attribute' => 'name',
			'value'     => function($model, $key, $index) {
				$result      = FHtml::showObjectPreview($model, FHtml::getModelPreviewFields($model, ['id']), true);
				$canEdit     = true;
				$object_type = '';

				//$result .= \common\widgets\FActionColumn::render($model, $key, $index, isset($actionLayout) ? $actionLayout : ($canEdit === false ? '{view}' : \yii\helpers\StringHelper::startsWith($object_type, 'object_') ? '{view}{edit}{delete}' : '{view}{edit}{unlink}{delete}'));
				return $result;
			},
		]
	];
}

$object_columns1 = array_merge([
	[
		'class' => 'kartik\grid\SerialColumn',
	]
], $object_columns1);
if (isset($columns)) {
	foreach ($columns as $column) {
		$object_columns1 = array_merge([['class' => 'kartik\grid\DataColumn', 'attribute' => $column]], $object_columns1);
	}
}

if ($canEdit) {
	$object_columns1 = array_merge($object_columns1, [
		[
			'class'        => 'common\widgets\FActionColumn',
			'dropdown'     => $is_modal ? 'modal' : 'iframe', // Dropdown or Buttons
			'actionLayout' => isset($actionLayout) ? $actionLayout : ($canEdit === false ? '{view}' : \yii\helpers\StringHelper::startsWith($object_type, 'object_') ? '{view}{edit}{delete}' : '{view}{edit}{unlink}{delete}'),
			'hAlign'       => 'center',
			'vAlign'       => 'middle',
			'width'        => '120px'
		]
	]);
}
$object_columns = $object_columns1;


//Register Javascript
if ($canEdit) {
	//FHtml::var_dump($related_condition); FHtml::var_dump($object_fields); die;
	$condition = array_merge($related_condition, ['relation_type' => $relation_type]);
	FHtml::registerPlusJS($object_type, $object_fields, $pjax_container, '{object}[{column}]', $condition);
	FHtml::registerResetJs($object_type, ['type' => null, $relation_field => 0], $pjax_container);
}
FHtml::registerUnlinkJs($object_type, ['relation_type' => $relation_type, 'object2_type' => $model_table, 'object2_id' => $model->id], $pjax_container);

$can_add_multiple = isset($can_add_multiple) ? $can_add_multiple : (\yii\helpers\StringHelper::startsWith($object_type, 'object_') ? false : true);
$model_label      = !empty($title) ? $title : FHtml::t('common', $_attribute);

$buttonCreateLabel = '+ ' . FHtml::t('button', 'Create') . ' ' . $model_label;
//Test
//$is_modal = true;
//$create_url = 'https://vnexpress.net';
$buttonCreate = $is_modal ? FHtml::buttonModal(FHtml::t('button', 'Create') . ' ' . $model_label, $create_url, 'iframe', 'btn btn-success') : FHtml::buttonLink('', '@' . $buttonCreateLabel, $create_url, 'success', false, 'iframe', ['pjax_container' => $grid_id . "-pjax"]);
$label        = FHtml::t('common', 'Add existing') . $model_label;

if (empty($title) || $title == FHtml::NULL_VALUE) {
	$title = $buttonCreate;
}
else {
	$title = "<div class='form-label'><div class='font-blue-madison bold uppercase pull-left'><h3>$model_label </h3></div><div class='pull-right' style='padding-top:15px'>$buttonCreate</div></div>";
}

?>
<div class="row">
	<?php if ($canEdit) { ?>

        <div class="col-md-<?= $form_width ?>">
			<?php /** @var FormFieldWidget $view_path_form */
			if (!empty($create_url)) {
				if (!$can_add_multiple) {
					echo $title;
				}
			}
			else if ($view_path_form == ""):
				$related_form = \common\widgets\FActiveForm::begin([
					'id'                     => $form_id . "-form",
					'type'                   => $form_type,
					'readonly'               => !$canEdit, // check the Role here
					'enableClientValidation' => true,
					'enableAjaxValidation'   => false,
					'is_modal'               => $is_modal,
					'action'                 => 'Create',
					'title'                  => '',
					'options'                => [
						'enctype' => 'multipart/form-data'
					]
				]);
				?>
                <div class="portlet light">

					<?php echo \common\widgets\FFormTable::widget([
						'model'      => $related_model,
						'form'       => $related_form,
						'columns'    => 1,
						'attributes' => !empty($object_attributes) ? $object_attributes : FHtml::getModelFormAttributes($related_model, $related_form, $object_fields)
					]); ?>
                    <hr />
					<?php
					echo FHtml::buttonCreateAjax($object_type, false, false, $pjax_container);
					echo $is_modal ? FHtml::buttonCloseModal() : '';

					?>

                </div>
				<?php \common\widgets\FActiveForm::end();
				?>
			<?php else: ?>
				<?= FHtml::render($view_path_form, '', ['model' => $model]); ?>
			<?php endif; ?>

        </div>
	<?php } else { ?>

	<?php } ?>
    <div class="col-md-<?= $list_width ?>">
		<?php if ($can_add_multiple) { ?>
            <div class="row">

				<?= $form->field($model, '_' . $_attribute . '_' . $relation_type)->label(false)->widget(MultipleInput::className(), [
					'min'               => 0,
					'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
					'columns'           => [
						[
							'name'          => 'name',
							'type'          => \kartik\select2\Select2::className(),
							'enableError'   => true,
							'title'         => $title,
							'options'       => FHtml::getSelect2Options('name', ['id' => 'id', 'description' => 'name'], $object_type, 'name', 'id', true),
							'headerOptions' => [
								'style' => 'border:none',
								'class' => 'col-md-11 caption-subject font-blue-madison uppercase'
							]
						],
						[
							'name'          => 'id',
							'options'       => [
								'style' => 'border:none;width:0px;visible:none',
							],
							'headerOptions' => [
								'style' => 'border:none;width:0px;visible:none',
							]
						],
					]
				])->label(false); ?>
            </div>
		<?php } ?>
		<?php /** @var FormFieldWidget $view_path_grid_view */
		if ($view_path_grid_view == ""): ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
					<?= \common\widgets\FGridView::widget([
						'id'             => $grid_id,
						'dataProvider'   => $data,
						'object_type'    => $object_type,
						'pjax'           => true,
						'view'           => 'list',
						'form_enabled'   => false,
						'form_view'      => '',
						'search_view'    => '',
						'emptyMessage'   => false,
						'registerJS'     => false, //already register JS outside
						'filterEnabled'  => false,
						'readonly'       => !$canEdit,
						'showHeader'     => $show_header,
						'default_fields' => $related_condition,
						'field_image'    => $field_image,
						'field_name'     => isset($field_name) ? $field_name : (isset($object_fields) ? $object_fields : ['name', 'title', 'overview', 'description']),
						'layout'         => '{items}{summary}{pager}',
						'edit_type'      => $canEdit ? FHtml::EDIT_TYPE_INLINE : FHtml::EDIT_TYPE_VIEW,
						'columns'        => $object_columns,


					]) ?>
                </div>
            </div>
		<?php else: ?>
			<?= FHtml::render($view_path_grid_view, '', ['model' => $model]); ?>
		<?php endif; ?>
    </div>
</div>