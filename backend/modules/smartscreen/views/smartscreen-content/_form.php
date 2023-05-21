<?php

/**



 * This is the customized model class for table "SmartscreenContent".
 */

use common\widgets\formfield\FormObjectFileAnpx1;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use common\components\FHtml;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\FFormTable;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SmartscreenContent';
$moduleTitle = 'Smartscreen Content';
$moduleKey = 'smartscreen-content';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);
$is_multipe_files = in_array($model->type, ['slide', 'audio', 'file', 'video', 'image']);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenContent */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t($moduleTitle);
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
} ?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>

<?php $form = FActiveForm::begin([
    'id' => 'smartscreen-content-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'edit_type' => $edit_type,
    'display_type' => $display_type,
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);
?>


<div class="form">
    <div class="row">

        <div class="col-md-9">
            <?php if (!FHtml::isViewAction($currentAction)) { ?>
                <div class="portlet light">
                    <div class="visible-print">
                        <?= (FHtml::isViewAction($currentAction)) ? FHtml::showPrintHeader($moduleName) : '' ?>
                    </div>
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase">
                                <?= FHtml::t('common', $moduleTitle) ?>
                                : <?= FHtml::showObjectConfigLink($model, FHtml::FIELDS_NAME) ?> </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">

                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'attributes' => [
                                                'title' => ['value' => $form->fieldNoLabel($model, 'title')->textInput(), 'columnOptions' => ['colspan' => 3]],

                                                'type' => ['readonly' => !$model->isNewRecord, 'value' => $form->fieldNoLabel($model, 'type')->select(\backend\modules\smartscreen\Smartscreen::getContentTypeComboArray()), 'columnOptions' => ['colspan' => 3]],
                                                'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox()],
                                                'is_default' => ['value' => $form->fieldNoLabel($model, 'is_default')->checkbox()],

                                                'url' => ['visible' => false, 'value' => $form->fieldNoLabel($model, 'url')->file(), 'columnOptions' => ['colspan' => 3]],
                                                'description' => ['visible' => !$model->isNewRecord && !$is_multipe_files, 'value' => $form->fieldNoLabel($model, 'description')->html(['rows' => 3]), 'columnOptions' => ['colspan' => 3]]
                                            ]]); ?>

                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                                '_scripts' => ['label' => FHtml::t('Content'), 'visible' => $is_multipe_files, 'value' => \common\widgets\formfield\FormObjectFile::widget(['accept_files' => "$model->type/*", 'view_path' => '_form_filesAnpx.php', 'model' => $model, 'form' => $form, 'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]), 'columnOptions' => ['colspan' => 3]],
                                            ]]); ?>

                                            <?php echo FFormTable::widget([
                                                'model' => $model, 'form' => $form, 'columns' => 2,
                                                'visible' => $model->type == 'text',  'attributes' => [
                                                    '_speed' => [
                                                        'label' => 'Scrolling  Speed', 'visible' => true || $model->type == 'text',
                                                        'value' => $form->fieldNoLabel($model, '_speed')->numericInput()->hint('50:slow,10:fast'),
                                                    ],
                                                    '_direction' => [
                                                        'label' => 'Scrolling Direction', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_direction')->dropDownList(['left', 'right', 'up', 'down', 'no-scroll', 'normal']),
                                                    ],
                                                    '_height' => [
                                                        'label' => 'Scrolling Height (px or %)', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_height')->textInput(),
                                                    ],
                                                    '_background' => [
                                                        'label' => 'Screen Background (Outside)', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_background')->color(),
                                                    ],
                                                    '_padding' => [
                                                        'label' => 'Padding (px or %)', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_padding')->textInput(),
                                                    ],
                                                    '_margin' => [
                                                        'label' => 'Margin (px or %)', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_margin')->textInput(),
                                                    ],
                                                    '_bgcolor' => [
                                                        'label' => 'Scrolling Background', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_bgcolor')->color(),
                                                    ],
                                                    '_color' => [
                                                        'label' => 'Font Color', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_color')->color(),
                                                    ],

                                                    '_font' => [
                                                        'label' => 'Font Family', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_font')->dropDownList(['Arial', 'Arial Black', 'Times New Roman', 'Courier', 'Courier New', 'Verdana', 'Georgia', 'Palatino', 'Garamond', 'Bookman', 'Tahoma', 'Impact', 'Comic Sans MS']),
                                                    ],

                                                    '_size' => [
                                                        'label' => 'Font Size  (px or vh or %)', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_size')->textInput(),
                                                    ],

                                                    '_scaleX' => [
                                                        'label' => 'Scale Width x', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_scaleX')->numericInput(),
                                                    ],
                                                    '_scaleY' => [
                                                        'label' => 'Scale Height x', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_scaleY')->numericInput(),
                                                    ],
                                                    '_style' => [
                                                        'label' => 'Font Style', 'visible' => $model->type == 'text',
                                                        'value'         => $form->fieldNoLabel($model, '_style')->selectMultiple(['normal', 'lighter', 'bold', 'bolder', 'italic', 'overline', 'line-through', 'underline', 'underline overline', 'center', 'justify', 'top', 'middle', 'bottom']),
                                                    ],
                                                ]
                                            ]); ?>


                                            <?php if ($model->isNewRecord) { ?>
                                                <br /><br />
                                                <input type="hidden" id="saveType" name="saveType">
                                                <script language="javascript" type="text/javascript">
                                                    function submitForm(saveType) {
                                                        $('#saveType').val(saveType);
                                                    }
                                                </script>
                                                <button type="submit" class="btn btn-primary" onclick="submitForm(&quot;save&quot;)"><i class="fa fa-save"></i> Tiếp tục</button>
                                                <a class="btn btn-default pull-right" href="<?= FHtml::createUrl('smartscreen/smartscreen-content/index') ?>" data-pjax="0"><i class="fa fa-undo"></i> Đóng</a>
                                            <?php } ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>


        </div>
        <div class="col-md-3">
            <?php if (!$model->isNewRecord) { ?>
                <?php $type = FHtml::getFieldValue($model, 'type');
                if (isset($modelMeta) && !empty($type))
                    echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
                ?>
                <?= (FHtml::isViewAction($currentAction)) ? FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
            <?php } ?>

            <?php if (!$model->isNewRecord) { ?>
                <div style="width: 100%; height: 250px; margin-bottom: 50px; background-color: #fefefe">
                    <div style="margin-right: 10px; float:right"><a target="_blank" href="<?= FHtml::createUrl('/smartscreen/content', ['id' => $model->id, 'layout' => 'no', 'auto_refresh' => 0]) ?>">Full screen</a> </div>
                    <iframe frameborder="0" src="<?= FHtml::createUrl('/smartscreen/content', ['id' => $model->id, 'layout' => 'no', 'auto_refresh' => 0]) ?>" width="100%" height="100%" />
                    <?php echo $model->getContent('contain') ?>
                </div>
            <?php  } ?>
        </div>
    </div>
</div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>