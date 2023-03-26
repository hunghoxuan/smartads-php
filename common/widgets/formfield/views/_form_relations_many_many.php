<?php

use common\components\FHtml;
use common\components\Helper;
use common\widgets\formfield\FormFieldWidget;
use unclead\multipleinput\MultipleInput;
use yii\widgets\Pjax;

?>

<?php

$object_type = isset($object_type) ? $object_type : 'object_relation';
if (!FHtml::isTableExisted($object_type)) {
	return;
}

if (in_array(FHtml::currentAction(), ['view'])) {
	$canEdit = false;
}

if (empty($field_name)) {
	$field_name = \yii\helpers\BaseInflector::camelize($object_type) . \yii\helpers\BaseInflector::camelize($relation_type);
}

$related_model = FHtml::createModel($object_type);

if (!isset($related_model) || !isset($model) || $model->isNewRecord || empty($model->id)) {
	echo FHtml::showAlert(FHtml::t('message', 'This feature is disabled in Create mode'));

	return;
}

$model_table = FHtml::getTableName($model);
$grid_id     = isset($grid_id) ? $grid_id : 'crud-datatable' . $field_name;

$pjax_container = isset($pjax_container) ? $pjax_container : $grid_id . '-pjax';
$form_id        = str_replace('_', '-', $object_type) . $pjax_container;

$label = FHtml::t('common', 'Add existing ' . $field_name) . (!empty($relation_type) ? ': ' . FHtml::t('common', \yii\helpers\BaseInflector::camel2words($relation_type)) : '');

$model_camelized_name = \yii\helpers\BaseInflector::camelize($model_table);
$field_camelized_name = \yii\helpers\BaseInflector::camelize($field_name);
$currentAction        = FHtml::currentAction();

$relation_type  = isset($relation_type) ? $relation_type : FHtml::RELATION_MANY_MANY;
$relation_field = isset($relation_type) && !empty($relation_type) ? $relation_type : 'object_id';

if (empty($relation_type)) {
	$related_condition = "((object_id = '$model->id' and object_type = '$model_table' and object2_type = '$object_type')) AND (relation_type = '' or relation_type is null)";
}
else {
	$related_condition = "(object_id = '$model->id' and object_type = '$model_table' and object2_type = '$object_type' and relation_type = '$relation_type')";
}

//echo $relation_type;
//echo $related_condition; die;

$relation_params = [
	'object_type'   => $model_table,
	'object_id'     => $model->id,
	'relation_type' => $relation_type,
	'object2_type'  => '{model.table}',
	'object2_id'    => '{model.id}',
	'sort_order'    => '0'
];
$data            = isset($data) ? $data : FHtml::getDataProvider('object_relation', $related_condition);

$object_fields = !empty($object_fields) ? $object_fields : FHtml::getModelPreviewFields($related_model);

$object_attributes = !empty($object_attributes) ? $object_attributes : [];

$canEdit = isset($canEdit) ? $canEdit : FHtml::isInRole($model_table, FHtml::ACTION_CREATE);

$ajax       = isset($ajax) ? $ajax : false;
$create_url = isset($create_url) ? $create_url : FHtml::createModelUrl($object_type, 'create', ['pjax_container' => "$grid_id-pjax", 'layout' => 'no', 'return_url' => FHtml::currentUrl()]);

$form_type   = isset($form_type) ? $form_type : \common\widgets\FActiveForm::TYPE_VERTICAL;
$is_modal    = isset($is_modal) ? $is_modal : ($object_type == $model_table ? false : false);
$is_multiple = isset($is_multiple) ? $is_multiple : false;

//setup layout
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

//setup columns
$grid_type = isset($grid_type) ? $grid_type : FHtml::DISPLAY_TYPE_GRID;

$object_columns1 = [];
if ($grid_type == FHtml::DISPLAY_TYPE_GRID) {
	$show_header      = true;
	$object_columns1  = isset($columns) ? $columns : FHtml::getModelGridColumns($related_model, $object_fields, 'object_relation');
	$data             = FHtml::getRelatedDataProvider($model_table, $model->id, $object_type, $relation_type);
	$grid_object_type = $object_type;
}
else {
	$show_header      = false;
	$object_columns1  = [
		[ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
		  'class'     => 'kartik\grid\DataColumn',
		  'edit_type' => FHtml::TYPE_DEFAULT,
		  'label'     => $label,
		  'format'    => 'html',
		  'attribute' => 'object2_id',
		  'value'     => function($model) { return FHtml::showObjectPreview($model, 'object2_id', 'object2_type', '', [], FHtml::isInRole(FHtml::getFieldValue($model, 'object2_type'), FHtml::ACTION_EDIT)); },
		]
	];
	$grid_object_type = $object_type;
}


$object_columns1 = array_merge([
	[
		'class' => 'kartik\grid\SerialColumn',
	]
], $object_columns1);
$object_columns1 = array_merge($object_columns1, $canEdit === false ? [] : [
	[
		'class'        => 'common\widgets\FActionColumn',
		'dropdown'     => $is_modal ? 'modal' : 'iframe', // Dropdown or Buttons
		'actionLayout' => isset($actionLayout) ? $actionLayout : ($canEdit === false ? '{view}' : '{view}{edit}{unlink}'),
		'object_type'  => $object_type,
		'hAlign'       => 'center',
		'vAlign'       => 'middle',
		'width'        => '120px'
	]
]);
$object_columns  = $object_columns1;

//Register Javascript
if ($canEdit) {
	FHtml::registerPlusJS($object_type, $object_fields, $pjax_container, '{object}[{column}]', ['objects' => FHtml::encode(['object_relation' => $relation_params])]);
	FHtml::registerPlusJS('object_relation', [], $pjax_container, '{object}[{column}]');
	FHtml::registerResetJs('object_relation', ['type' => null, $relation_field => 0], $pjax_container);
	FHtml::registerUnlinkJs($object_type, ['relation_type' => $relation_type, 'object2_type' => $model_table, 'object2_id' => $model->id], $pjax_container);
	$can_add_multiple = isset($can_add_multiple) ? $can_add_multiple : (\yii\helpers\StringHelper::startsWith($object_type, 'object_') ? false : true);

}
else {
	$can_add_multiple = false;
}

$model_label       = !empty($title) ? $title : FHtml::t('common', $field_name) . (!empty($relation_type) ? ': ' . FHtml::t('common', \yii\helpers\BaseInflector::camel2words($relation_type)) : '');
$buttonCreateLabel = '+ ' . FHtml::t('button', 'Create') . ' ' . $model_label;
$buttonCreate      = $is_modal ? FHtml::buttonModal($buttonCreateLabel, $create_url, 'modal-remote', 'btn btn-success') : FHtml::buttonLink('', '@' . $buttonCreateLabel, $create_url, 'success', false, 'iframe', ['pjax_container' => $grid_id . "-pjax"]);
$label             = FHtml::t('common', 'Add existing') . $model_label;
$label             = FHtml::t('common', 'Add existing') . $model_label;

if (empty($title) || $title == FHtml::NULL_VALUE) {
	$title = $buttonCreate;
}
else {
	$title = "<div class='form-label'><div class='font-blue-madison bold uppercase pull-left'> <h3>$model_label </h3></div><div class='pull-right' style='margin-top:15px'>$buttonCreate</div></div>";
}

?>

<div class="row">
	<?php if ($canEdit) { ?>

        <div class="col-md-<?= $form_width ?> hidden-print">
			<?php /** @var FormFieldWidget $view_path_form */
			if (!empty($create_url)) {
				if ($is_modal && !$can_add_multiple) {
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
					'options'                => [
						'enctype' => 'multipart/form-data'
					]
				]);
				?>
                <div class="portlet light">

                    <div class="portlet-title hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison uppercase">
                                    <?= $model_label ?>
                                </span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="collapse"></a>
                        </div>

                    </div>
                    <div class="portlet-body form" style="<?= $form_css_style ?>">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1" style="padding:10px">

										<?php echo \common\widgets\FFormTable::widget([
											'model'      => $related_model,
											'form'       => $related_form,
											'columns'    => 1,
											'attributes' => !empty($object_attributes) ? $object_attributes : FHtml::getModelFormAttributes($related_model, $related_form, $object_fields)
										]); ?>

                                        <hr />
										<?php echo FHtml::buttonCreateAjax($object_type, false, false, $pjax_container);

										?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
				<?php \common\widgets\FActiveForm::end(); ?>
			<?php else: ?>
				<?= FHtml::render($view_path_form, '', ['model' => $model]); ?>
			<?php endif; ?>
        </div>
		<?php

	}
	else { ?>
	<?php } ?>
    <div class="col-md-<?= $list_width ?>">
		<?php if ($can_add_multiple && $canEdit) {
			//$field_camelized_name = \yii\helpers\BaseInflector::camelize($object_type);
			?>

            <div class="row hidden-print">
                <input type="hidden" value="<?= $relation_type ?>" name='<?= "$model_camelized_name" . "[_" . $field_camelized_name . "_RelationType]" ?>' id="<?= strtolower($model_camelized_name . '_' . $field_camelized_name) . '_relation_type' ?>" />
                <input type="hidden" value="<?= $object_type ?>" name='<?= "$model_camelized_name" . "[_" . $field_camelized_name . "_ObjectType]" ?>' id="<?= strtolower($model_camelized_name . '_' . $field_camelized_name) . '_object_type' ?>" />

				<?php if ($is_multiple) {
					echo $form->field($model, '_' . $field_name . '_' . $relation_type)->label(false)->widget(\kartik\select2\Select2::className(), [
						'data'    => FHtml::getComboArray('@' . $object_type),
						'options' => ['multiple' => true, 'placeholder' => FHtml::t('common', $label)]
					]);
				}
				else {
					echo $form->field($model, '_' . $field_name . '_' . $relation_type)->label(false)->widget(MultipleInput::className(), [
						'min'               => 0,
						'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
						'columns'           => [
							[
								'name'          => 'name',
								'type'          => \kartik\select2\Select2::className(),
								'enableError'   => true,
								'title'         => '<div style="margin-top:-35px">' . $title . '</div>',
								'options'       => FHtml::getSelect2Options('name', ['id' => 'id', 'description' => 'name'], $object_type, 'name', 'id', true),
								'headerOptions' => [
									'style' => 'border:none; border_bottom:1px lightgray',
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
					])->label(false);
				}
				?>
            </div>
		<?php }
		?>
		<?php /** @var FormFieldWidget $view_path_grid_view */
		if (empty($view_path_grid_view)) {
			?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
					<?= \common\widgets\FGridView::widget([
						'id'           => $grid_id,
						'dataProvider' => $data,
						'showHeader'   => $show_header,
						'readonly'     => !$canEdit,

						'object_type'    => $grid_object_type,
						'field_name'     => isset($object_fields) ? $object_fields : ['name', 'title', 'overview', 'description'],
						//'field_description' => ['overview', 'description'],
						//'field_group' => ['category_id', 'type', 'status', 'is_hot', 'is_top', 'is_active'],
						'view'           => 'list',
						'emptyMessage'   => false,

						//'display_type' => FHtml::DISPLAY_TYPE_WIDGET,
						'pjax'           => true,
						'form_enabled'   => false,
						'filterEnabled'  => false,
						'default_fields' => [],
						'layout'         => '{items}{summary}{pager}',
						'edit_type'      => $canEdit ? FHtml::EDIT_TYPE_INLINE : FHtml::EDIT_TYPE_VIEW,
						'columns'        => $object_columns
					]) ?>
                </div>
            </div>
		<?php } else { ?>

			<?= FHtml::render($view_path_grid_view, '', ['model' => $model]); ?>
		<?php } ?>
    </div>

</div>
