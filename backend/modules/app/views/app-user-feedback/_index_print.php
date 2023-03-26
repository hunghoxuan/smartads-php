<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;

use common\widgets\FGridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\app\models\AppUserFeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'AppUserFeedback';
$moduleTitle = 'App User Feedback';
$moduleKey = 'app-user-feedback';
$moduleModel = 'app_user_feedback';

$this->title = FHtml::t($moduleTitle);

$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only

if (FHtml::isInRole('', 'update', $currentRole) && FHtml::settingAdminInlineEdit() == true)
{
    $gridControl = $folder.'_columns.php';
}
else
{
    $gridControl = $folder.'_columns.php';
}

?>
<div class="app-user-feedback-index visible-print">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['form_type' => $moduleName, 'title' => FHtml::t('common', 'List')]) ?>            <div id="ajaxCrudDatatable" class="">
                <?=FGridView::widget([
                'id'=>'crud-datatable-print',
                //'floatHeader' => true, // enable this will keep header when scroll but disable resizeable column feature
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'object_type' => $moduleModel,
                'pjax' => false,
                'pager' => [
                    'firstPageLabel' => 'First',
                    'lastPageLabel' => 'Last',
                ],
                'display_type' => 'print',
                'field_name' => ['name', 'title'],
                'field_description' => ['overview', 'description'],
                'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                'field_business' => ['', ''],
                'floatHeader'=>false,
                'hover' => true,
                'toolbar' => null,
                'columns' => require(__DIR__.'/'.$gridControl),
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'bordered'=> true,
                'showPageSummary'=> false,
                'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'footerRowOptions'=>['class'=>'kartik-sheet-style'],
                'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                'layout' => "{toolbar}\n{items}\n{summary}\n{pager}",
                'panel' => false
                ])?>
            </div>
        </div>
    </div>
</div>


