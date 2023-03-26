<?php

use yii\bootstrap\Modal;
use common\widgets\FGridView;
use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ObjectCollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'ObjectCollection';
$moduleTitle = 'Object Collection';
$moduleKey = 'object-collection';
$object_type = 'object_collection';

$this->title = FHtml::t($moduleTitle);

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
$viewType = isset($viewType) ? $viewType : FHtml::getRequestParam('view');
$gridControl = $folder . '_columns.php';

?>

<div class="object-collection-index">
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
                <div class="col-md-12">
                    <div id="ajaxCrudDatatable" class="<?= !$this->params['displayPortlet'] ? 'portlet light ' . ($viewType != 'print' ? 'bordered' : '') : ''; ?>">
                        <?= FGridView::widget([
	                        'id' => 'crud-datatable',
	                        'dataProvider' => $dataProvider,
	                        'filterModel' => $searchModel,
	                        'form_view' => '_form_add',
	                        'object_type' => $object_type,
	                        'edit_type' => FHtml::EDIT_TYPE_INLINE,
	                        'render_type' => FHtml::RENDER_TYPE_AUTO,
	                        'readonly' => !FHtml::isInRole('', 'update', $currentRole),
	                        'field_name' => ['name', 'title'],
	                        'field_description' => ['overview', 'description'],
	                        'field_group' => ['category_id', 'type', 'status', 'is_hot', 'is_top', 'is_active'],
	                        'field_business' => ['', ''],
	                        'toolbar' => $this->render('_toolbar.php'),
	                        'columns' => require(__DIR__.'/'.$gridControl),
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