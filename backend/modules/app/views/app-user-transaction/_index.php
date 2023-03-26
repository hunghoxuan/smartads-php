<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\widgets\FGridView;

use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\app\models\AppUserTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'AppUserTransaction';
$moduleTitle = 'App User Transaction';
$moduleKey = 'app-user-transaction';
$moduleModel = 'app_user_transaction';

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
    $gridControl = $folder.'_columns_editable.php';
}
else
{
    $gridControl = $folder.'_columns.php';
}

?>
<div class="app-user-transaction-index">
    <?php  if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title">
            <div class="caption font-dark">
                <span class="caption-subject bold uppercase">
                <i class="<?php  echo $this->params['mainIcon'] ?>"></i>
                <?= FHtml::t('common', $moduleTitle)?></span>
                <span class="caption-helper"><?=  FHtml::t('common', 'title.index') ?></span>
            </div>
            <div class="tools">
                <a href="#" class="fullscreen"></a>
                <a href="#" class="collapse"></a>
            </div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body">
        <?php  endif; ?>            <div class="row">
                <div class="col-md-12">
                    <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light';  ?>">
                        <?=FGridView::widget([
                        'id'=>'crud-datatable',
                        'floatHeader' => false, // enable this will keep header when scroll but disable resizeable column feature
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'object_type' => $moduleModel,
                        'pjax' => true,
                        'pager' => [
                            'firstPageLabel' => 'First',
                            'lastPageLabel' => 'Last',
                        ],
                        'field_name' => ['name', 'title'],
                        'field_description' => ['overview', 'description'],
                        'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                        'field_business' => ['', ''],
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
    <?php  if ($this->params['displayPortlet']): ?>        </div>
    </div>
    <?php  endif; ?></div>
    <?php Modal::begin([
    "id"=>"ajaxCrubModal",
    "footer"=>"",
    ])?>
    <?php Modal::end(); ?>
