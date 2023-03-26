<?php

use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormRelations;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type   = ActiveForm::TYPE_HORIZONTAL;
$moduleName  = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey   = 'object-category';

$role   = isset($role) ? $role : FHtml::getCurrentRole();
$action = isset($action) ? $action : FHtml::currentAction();

$canEdit      = isset($canEdit) ? $canEdit : FHtml::isInRole($model, 'edit', $role, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete    = isset($canDelete) ? $canDelete : FHtml::isInRole($model, 'delete', $role);
$edit_type    = isset($edit_type) ? $edit_type : (FHtml::isViewAction($action) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($action) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);
$view_type    = isset($view_type) ? $view_type : FHtml::getRequestParam(['form_width', 'layout']);

	$col_size1 = 12;
	$col_size2 = 0;

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($action) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCategory */
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
	'id'                     => 'object-category-form',
	'type'                   => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
	'formConfig'             => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
	'staticOnly'             => false, // check the Role here
	'readonly'               => !$canEdit, // check the Role here
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
						<?= FHtml::isViewAction($action) ? FHtml::showPrintHeader($moduleName) : '' ?>
                    </div>
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase">
                                <?= FHtml::t('common', $moduleTitle) . ":" . FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?>
                            </span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                            </li>
                            <li class="">
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Content') ?></a>
                            </li>
                            <li class="">
                                <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'SEO') ?></a>
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
												'columns'    => 3,
												'title' => false,
												'attributes' => [
													'name'        => [
														'value'         => $form->fieldNoLabel($model, 'name')->textInput()->hint('display label'),
														'columnOptions' => ['colspan' => 3],
														'type'          => FHtml::INPUT_RAW
													],
                                                    'code'        => [
                                                        'value'         => $form->fieldNoLabel($model, 'code')->textInput()->hint('value saved in database'),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
												]
											]); ?>


                                            <?= FFormTable::widget([
                                                'model'      => $model,
                                                'title'      => false,
                                                'form'       => $form,
                                                'columns'    => 3,
                                                'attributes' => [
                                                    'is_active'   => [
                                                        'value'         => $form->fieldNoLabel($model, 'is_active')->checkbox(),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'is_top'      => [
                                                        'value'         => $form->fieldNoLabel($model, 'is_top')->checkbox(),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'is_hot'      => [
                                                        'value'         => $form->fieldNoLabel($model, 'is_hot')->checkbox(),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                ]
                                            ]); ?>

                                            <?= FFormTable::widget([
                                                'model'      => $model,
                                                'title'      => FHtml::t('common', 'GROUP'),
                                                'form'       => $form,
                                                'columns'    => 1,
                                                'open' => empty(FHtml::getRequestParam('object_type')),
                                                'attributes' => [
                                                    'object_type' => [
                                                        'value'         => $form->fieldNoLabel($model, 'object_type')
                                                            ->textInput(),
                                                        'readonly' => !$model->isNewRecord,
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],

                                                ]
                                            ]); ?>


                                        </div>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                            <?= FFormTable::widget([
                                                'model'      => $model,
                                                'title'      => false,
                                                'form'       => $form,
                                                //'type'       => ActiveForm::TYPE_HORIZONTAL,
                                                'columns'    => 1,
                                                'attributes' => [

                                                    'description' => [
                                                        'value'         => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3]),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'content' => [
                                                        'value'         => $form->fieldNoLabel($model, 'content')->textarea(['rows' => 10]),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'image' => ['value' => $form->fieldNoLabel($model, 'image')->image()],
                                                    'parent_id'   => [
                                                        'value'         => $form->fieldNoLabel($model, 'parent_id')
                                                            ->select(FHtml::getComboArray('object_category', 'object_category', 'parent_id', true, 'id', 'name')),
                                                        'columnOptions' => ['colspan' => 1],
                                                        'type'          => FHtml::INPUT_RAW
                                                    ],
                                                    'color' => ['value' => $form->fieldNoLabel($model, 'color')->color()],

                                                ]
                                            ]); ?>

                                        </div>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_3">
                                        <div class="col-md-12">
                                            <?= \common\widgets\FFormSEO::widget(['model' => $model, 'form' => $form, 'title' => false]) ?>
                                        </div>
                                    </div>
                                </div>
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
				<?= FHtml::isViewAction($action) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
            </div>
        </div>
    </div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) {
	Pjax::end();
} ?>