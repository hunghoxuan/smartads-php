<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ObjectTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'ObjectType';
$moduleTitle = 'Object Type';
$moduleKey = 'object-type';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

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
<div class="object-type-index">
    <?php  if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
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
        <?php  endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light'; ?>">

                        <?=\common\widgets\FGridView::widget([
                        'id'=>'crud-datatable',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'pjax' => true,
                        'object_type' => 'object_type',
                        'toolbar' => require(__DIR__ . '/_toolbar.php'),
                        'columns' => require(__DIR__ . '/' . $gridControl),
                        'striped' => true,
                        'condensed' => true,
                        'responsive' => true,
                        'bordered'=> true
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
