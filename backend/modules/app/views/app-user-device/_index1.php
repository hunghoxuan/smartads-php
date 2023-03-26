<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\widgets\FGridView;

use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\app\models\AppUserDeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = isset($moduleName) ? $moduleName : 'AppUserDevice';
$moduleTitle = isset($moduleTitle) ? $moduleTitle : 'App User Device';
$moduleKey = isset($moduleKey) ? $moduleKey : 'app-user-device';
$object_type = isset($object_type) ? $object_type : 'app_user_device';

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
$viewType = isset($viewType) ? $viewType : FHtml::getRequestParam('view');
$gridControl = $folder . '_columns.php';

?>
<div class="row">
    <div class="col-md-2 col-xs-12">
        <?= FHtml::buildGridFiltersVertical($object_type, ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top']) ?>    </div>
    <div class="col-md-10 col-xs-12">
        <div class="app-user-device-index">
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
                    <?php  endif; ?>                    <div class="row">
                        <div class="col-md-12">
                            <div id="ajaxCrudDatatable"
                                 class="<?php if (!$this->params['displayPortlet']) echo 'portlet light ' . ($viewType != 'print' ? 'bordered' : '');  ?>">
                                <?=FGridView::widget([
                                'id'=>'crud-datatable',
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'object_type' => $object_type,
                                'edit_type' => FHtml::EDIT_TYPE_INLINE,
                                'render_type' => FHtml::RENDER_TYPE_AUTO,
                                'readonly' => !FHtml::isInRole('', 'update', $currentRole),
                                'field_name' => ['name', 'title'],
                                'field_description' => ['overview', 'description'],
                                'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                                'field_business' => ['', ''],
                                'toolbar' => $this->render('_toolbar.php'),
                                'columns' => require(__DIR__.'/'.$gridControl),
                                ])?>
                            </div>
                        </div>
                    </div>
                    <?php  if ($this->params['displayPortlet']): ?>                </div>
            </div>
            <?php  endif; ?>        </div>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrubModal",
    "footer"=>"",
    ])?>
<?php Modal::end(); ?>
