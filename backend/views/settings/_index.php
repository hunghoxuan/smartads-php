<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';

$this->title = FHtml::t('common', $moduleTitle);

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
$gridControl = $folder . '_columns.php';

?>
<div class="settings-index">
    <?php if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
                <?= FHtml::renderView('_toolbar.php'); ?>
            </div>
            <div class="tools">
                <a href="#" class="fullscreen"></a>
                <a href="#" class="collapse"></a>
            </div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body">
            <?php endif; ?>
            <div class="row">

                <div class="col-md-10" style="padding-right: 50px">
                    <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light'; ?>">
                        <?= \common\widgets\FGridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => null,
                            'pjax' => true,
                            'pager' => null,
                            'object_type' => $moduleKey,
                            'edit_type' => FHtml::EDIT_TYPE_INPUT,
                            'form_enabled' => false,
                            'floatHeader' => false,
                            'hover' => false,
                            'toolbar' => null,
                            'filter' => null,
                            'columns' => require(__DIR__ . '/' . $gridControl),
                            'striped' => false,
                            'condensed' => true,
                            'responsive' => true,
                            'bordered' => true,
                            'showPageSummary' => false,
                            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                            'layout' => "{toolbar}\n{items}",
                            'panel' => false
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-2 form-label fixed-right-side">
                    <div class="">
                        <?php echo FHtml::showTabsList(FHtml::SETTINGS_GROUPS, 'group'); ?>
                    </div>
                </div>
            </div>
            <?php if ($this->params['displayPortlet']): ?>        </div>
    </div>
<?php endif; ?></div>

