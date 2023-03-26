<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Application';
$moduleTitle = 'Application';
$moduleKey = 'application';

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
<div class="application-index">
    <div class="row">
        <div class="col-md-12">
            <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light'; ?>">
                <?= \common\widgets\FGridView::widget([
                'id'=>'',
                //'floatHeader' => true, // enable this will keep header when scroll but disable resizeable column feature
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'pjax' => false,
                'display_type' => FHtml::DISPLAY_TYPE_PRINT,
                'columns' => require(__DIR__ . '/' .$gridControl),
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


