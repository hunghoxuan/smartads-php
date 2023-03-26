<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ObjectFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'ObjectFile';
$moduleTitle = 'Object File';
$moduleKey = 'object-file';

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
<div class="object-file-index">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['form_type' => $moduleName, 'title' => FHtml::t('common', 'List')]) ?>
            <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light'; ?>">
                <?=GridView::widget([
                'id'=>'',
                //'floatHeader' => true, // enable this will keep header when scroll but disable resizeable column feature
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'pjax' => false,
                'pager' => [
                    'firstPageLabel' => 'First',
                    'lastPageLabel' => 'Last',
                ],
                'floatHeader'=>false,
                'hover' => false,
                'filterPosition'=>'',
                'toolbar' => null,
                'columns' => require(__DIR__ . '_index_print.php/' .$gridControl),
                'striped' => false,
                'condensed' => true,
                'responsive' => false,
                'bordered'=> true,
                'showPageSummary'=> false,
                'layout' => "{toolbar}\n{items}\n{summary}\n{pager}",
                'panel' => false,
                'sorter' => false
                ])?>
            </div>
        </div>
    </div>
</div>


