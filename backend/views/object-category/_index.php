<?php

use yii\bootstrap\Modal;
use common\widgets\FGridView;
use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\models\models\ObjectCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';

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
$object_type_array = isset($object_type_array) ? $object_type_array : \backend\models\ObjectCategory::getLookupArray('#object_type');
$object_type_array = array_values($object_type_array);

$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');

$size1 = count($object_type_array) == 0 ? 0: 2;
$size2 = 12 - $size1;
?>

<div class="object-category-index">
    <?php if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title font-blue-madison bold uppercase">
                <?= FHtml::buildAdminToolbar(empty($object_type) ? $moduleKey : $object_type, ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top']) ?>            </div>
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
                <?php if ($size1 > 0) { ?>
                <div class="col-md-<?= $size1 ?> hidden-print">
                    <?php
                    echo FHtml::showCategoryList($object_type_array, 'object_type');
                    ?>
                </div>
                <?php } ?>
                <div class="col-md-<?= $size2 ?>">
                    <div id="ajaxCrudDatatable" class="<?= !$this->params['displayPortlet'] ? 'portlet light ' . ($viewType != 'print' ? 'bordered' : '') : ''; ?>">
                        <?= FGridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'toolbar' => $this->render('_toolbar.php', ['object_type' => $object_type]),
                            'columns' => require(__DIR__ . '/' . $gridControl),
                            'edit_type' => FHtml::EDIT_TYPE_INLINE,
                            'filter' => [],
                            'views' => []


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