<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;

use common\widgets\FGridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\app\models\AppUserProSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'AppUserPro';
$moduleTitle = 'App User Pro';
$moduleKey = 'app-user-pro';
$moduleModel = 'app_user_pro';

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
<div class="app-user-pro-index">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['form_type' => $moduleName, 'title' => FHtml::t('common', 'List')]) ?>            <div id="ajaxCrudDatatable" class="">
                <?=FGridView::widget([
                'id'=>'crud-datatable',
                //'floatHeader' => true, // enable this will keep header when scroll but disable resizeable column feature
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'object_type' => $moduleModel,
                'pjax' => true,
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
                'toolbar' => require(__DIR__ . '/_toolbar.php'),
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


