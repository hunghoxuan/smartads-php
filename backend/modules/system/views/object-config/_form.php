<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "ObjectActions".
*/
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'ObjectConfig';
$moduleTitle = 'Object Configs';
$moduleKey = 'object-configs';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);
$object_id = isset($object_id) ? $object_id : FHtml::getRequestParam('object_id');
$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');

$model = isset($model) ? $model : FHtml::getModel($object_type, '', $object_id);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectActions */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
$this->title = FHtml::t($moduleTitle);
$this->params['mainIcon'] = 'fa fa-list';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
} ?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php

$object_id = isset($object_id) ? $object_id : FHtml::getRequestParam('object_id');
$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');

$objectActionsSearchModel = new \backend\models\ObjectActionsSearch();
$objectActionsDataProvider = $objectActionsSearchModel->search(['object_id' => $object_id, 'object_type' => $object_type]);
 ?>


<div class="form">
    <div class="row">

        <div class="col-md-12">
            <div class="portlet light">
                <div class="visible-print">
                    <?= (FHtml::isViewAction($currentAction)) ?  FHtml::showPrintHeader($moduleName) : ''  ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?> : <?= FHtml::showModelField($model, 'name') ?>                        </span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'History')?></a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Uploads')?></a>
                        </li>
                                                </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= \common\widgets\FGridView::widget([
                                            'id'=>'crud-datatable',
                                            'dataProvider' => $objectActionsDataProvider,
                                            'filterModel' => $objectActionsSearchModel,
                                            'object_type' => $object_type,
                                            'edit_type' => FHtml::EDIT_TYPE_INLINE,
                                            'render_type' => FHtml::RENDER_TYPE_AUTO,
                                            'readonly' => !FHtml::isInRole('', 'update', $currentRole),
                                            'field_name' => ['name', 'title'],
                                            'field_description' => ['overview', 'description'],
                                            'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                                            'field_business' => ['', ''],
                                            'toolbar' => $this->render('_toolbar.php'),
                                            'columns' => require(__DIR__.'/_columns.php'),
                                        ])?>

                                        
                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">


                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>

    </div>
</div>
<?php if ($ajax) Pjax::end()  ?>
