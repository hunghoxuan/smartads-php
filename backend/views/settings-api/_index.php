<?php

use yii\bootstrap\Modal;
use common\widgets\FGridView;
use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\system\models\SettingsApiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SettingsApi';
$moduleTitle = 'Settings Api';
$moduleKey = 'settings-api';
$object_type = 'settings_api';

$this->title = FHtml::t($moduleTitle);

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$role = isset($role) ? $role : FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
$viewType = isset($viewType) ? $viewType : FHtml::getRequestParam('view');
$gridControl = $folder . '_columns.php';
$modules = array_merge(['api' => 'API'], FHtml::getApplicationModulesComboArray());

?>

<div class="settings-api-index">
    <?php if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title font-blue-madison bold uppercase">
                <?= FHtml::buildAdminToolbar($object_type, ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top']) ?>            </div>
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
                <div class="col-md-2 hidden-print">
                    <?php
                    echo FHtml::showCategoryList($modules, 'module');
                    ?>
                </div>
                <div class="col-md-10">
                    <div id="ajaxCrudDatatable" class="<?= !$this->params['displayPortlet'] ? 'portlet light ' . ($viewType != 'print' ? 'bordered' : '') : ''; ?>">
                        <?= FGridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'filter' => [],
                            'toolbar' => $this->render('_toolbar.php'),
                            'columns' => require(__DIR__ . '/' . $gridControl),
                            'views' => [],

                            //'object_type' => $object_type,
                            //'readonly' => !FHtml::isInRole('', 'update', $role),
                            //'field_name' => ['name', 'title'],
                            //'field_description' => ['overview', 'description'],
                            //'field_group' => ['category_id', 'type', 'status', 'is_hot', 'is_top', 'is_active'],
                            //'field_business' => ['', ''],
                            //'view' => 'grid'

                        ]) ?>
                    </div>
                </div>
            </div>
            <?php if ($this->params['displayPortlet']): ?>
        </div>
    </div>
<?php endif; ?>
</div>
<?php Modal::begin([
    "id" => "ajaxCrubModal",
    "footer" => "",
]) ?>
<?php Modal::end(); ?>