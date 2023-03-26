<?php

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormRelations;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type   = $this->params['activeForm_type'];
$moduleName  = 'ObjectCollection';
$moduleTitle = 'Object Collection';
$moduleKey   = 'object-collection';

$currentRole   = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit      = isset($canEdit) ? $canEdit : FHtml::isInRole($model, 'edit', $currentRole, FHtml::getFieldValue($model, [
	'user_id',
	'created_user'
]));
$canDelete    = isset($canDelete) ? $canDelete : FHtml::isInRole($model, 'delete', $currentRole);
$edit_type    = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);
$view_type    = isset($view_type) ? $view_type : FHtml::getRequestParam('view_type');

if ($view_type == 'full') {
	$col_size1 = 12;
	$col_size2 = 0;
}
else {
	$col_size1 = 9;
	$col_size2 = 3;
}
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCollection */
/* @var $form common\widgets\FActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
	$this->title                    = FHtml::t($moduleTitle);
	$this->params['mainIcon']       = 'fa fa-list';
	$this->params['toolBarActions'] = array(
		'linkButton' => array(),
		'button'     => array(),
		'dropdown'   => array(),
	);
} ?>
<?php if ($ajax) {
	Pjax::begin(['id' => 'crud-datatable']);
} ?>
<?php $form = FActiveForm::begin([
	'id'                     => 'object-collection-form',
	'type'                   => $form_Type,
	//ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
	'formConfig'             => [
		'labelSpan'  => 3,
		'deviceSize' => ActiveForm::SIZE_MEDIUM,
		'showErrors' => true
	],
	'staticOnly'             => false,
	// check the Role here
	'readonly'               => !$canEdit,
	// check the Role here
	'edit_type'              => $edit_type,
	'display_type'           => $display_type,
	'enableClientValidation' => true,
	'enableAjaxValidation'   => false,
	'options'                => [
		'enctype' => 'multipart/form-data'
	]
]);
?>
    <div class="form">
        <div class="row">
            <div class="col-md-<?= $col_size1 ?>">
                <div class="portlet light">
                    <div class="visible-print">
						<?= FHtml::isViewAction($currentAction) ? FHtml::showPrintHeader($moduleName) : '' ?>
                    </div>
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) . ":" . FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
											<?= FFormTable::widget([
												'model'      => $model,
												'form'       => $form,
												'columns'    => 2,
												'attributes' => [
													'name'        => [
														'value'         => $form->fieldNoLabel($model, 'name')->textInput(),
														'columnOptions' => ['colspan' => 1],
														'type'          => FHtml::INPUT_RAW
													],
													'object_type' => [
														'value'         => $form->fieldNoLabel($model, 'object_type')
														                        ->select(FHtml::getComboArray('object_collection', 'object_collection', 'object_type', true, 'id', 'name')),
														'columnOptions' => ['colspan' => 1],
														'type'          => FHtml::INPUT_RAW
													],
													'is_active'   => [
														'value'         => $form->fieldNoLabel($model, 'is_active')->checkbox(),
														'columnOptions' => ['colspan' => 1],
														'type'          => FHtml::INPUT_RAW
													],
													'description' => [
														'value'         => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3]),
														'columnOptions' => ['colspan' => 1],
														'type'          => FHtml::INPUT_RAW
													],
												]
											]); ?>
                                        </div>
                                    </div>
                                </div>
								<?php
								/** @var \common\models\BaseModel $modelRelated */
								$modelRelated = \common\components\FModel::getModel($model->object_type);
								$attributes   = $modelRelated::createNew()->getAttributes();

								$fields = array_merge(FHtml::FIELDS_TEXTAREA, FHtml::FIELDS_COMMON, FHtml::FIELDS_HIDDEN, FHtml::FIELDS_LOOKUP, FHtml::FIELDS_HTML, FHtml::FIELDS_IMAGES, FHtml::FIELDS_COUNT, FHtml::FIELDS_FILES);
								foreach ($fields as $key => $field) {
									$field = str_replace('*', "", $field);
									if (key_exists($field, $attributes)) {
										unset($attributes[$field]);
									}

									unset($attributes['author']);
								}

								//                                $attributes = $modelRelated::createNew()->getPreviewFields();

								?>
								<?= FormRelations::widget([
									'model'         => $model,
									'form'          => $form,
									'form_type'     => FActiveForm::TYPE_VERTICAL,
									'grid_type'     => 'grid',
									'field_name'    => \yii\helpers\BaseInflector::camelize($model->object_type),
									'object_type'   => $model->object_type,
									'relation_type' => '',
									'canEdit'       => $canEdit,
									'moduleKey'     => $moduleKey,
									'modulePath'    => $modulePath,
									'object_fields' => $attributes
								]) ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php $type = FHtml::getFieldValue($model, 'type');
				if (isset($modelMeta) && !empty($type)) { ?>
					<?= FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', [
						'model'           => $modelMeta,
						'display_actions' => false,
						'canEdit'         => $canEdit,
						'canDelete'       => $canDelete
					]); ?>
				<?php } ?>
				<?= FHtml::isViewAction($currentAction) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
            </div>
			<?php if ($col_size2 > 0) { ?>
                <div class="profile-sidebar col-md-<?= $col_size2 ?> col-xs-12 hidden-print">
                    <div class="portlet light">
						<?= FHtml::showModelPreview($model) ?>
                    </div>
                </div>
			<?php } ?>
        </div>
    </div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) {
	Pjax::end();
} ?>