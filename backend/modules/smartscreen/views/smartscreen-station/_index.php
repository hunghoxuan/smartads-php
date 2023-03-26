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
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenStationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SmartscreenStation';
$moduleTitle = 'Smartscreen Station';
$moduleKey = 'smartscreen-station';
$moduleModel = 'smartscreen_station';

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

$gridControl = $folder . '_columns.php';

?>

<div class="smartscreen-station-index">
    <?php if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title">
            <div class="caption font-dark">
                <?= FHtml::buildAdminToolbar('smartscreen_station', ['channel_id'], false) ?>

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
                <div class="col-md-12">
                    <div id="ajaxCrudDatatable"
                         class="<?php if (!$this->params['displayPortlet']) echo 'portlet light bordered'; ?>">
                        <?= FGridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'object_type' => $moduleModel,
                            'pjax' => true,
                            'edit_type' => FHtml::EDIT_TYPE_INLINE,
                            'filter' => ['channel_id', 'status'],
                            'render_type' => FHtml::RENDER_TYPE_AUTO,
                            'readonly' => !FHtml::isInRole($moduleName, 'edit', $currentRole),
                            'field_name' => ['name', 'title'],
                            'field_description' => ['overview', 'description'],
                            'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                            'field_business' => ['', ''],
                            'toolbar' => require(__DIR__ . '/_toolbar.php'),
                            'columns' => require(__DIR__ . '/' . $gridControl),
                        ]) ?>
                    </div>
                </div>
            </div>
            <?php if ($this->params['displayPortlet']): ?>        </div>
    </div>
<?php endif; ?></div>
<?php Modal::begin([
    "id" => "ajaxCrubModal",
    "footer" => "",
]) ?>
<?php Modal::end(); ?>


<?php
//$this->registerJsFile("/SmartAds-Stech-PHP/node/node_modules/socket.io/socket.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
//
//$script = <<< JS
//    var socket = io.connect('http://localhost:8890');
//
//    socket.on('notification', function (data) {
//        var data = JSON.parse(data);
//        var ime = '.ime_' + data.device;
//
//		console.log(data);
//
//        if (data.message == 0) {
//            $(ime).html('<span class="glyphicon glyphicon-remove text-danger"></span>');
//        }else if(data.message == 1){
//            $(ime).html('<span class="glyphicon glyphicon-ok text-success"></span>');
//        }
//    });
//JS;
//
//$this->registerJs($script);

?>
