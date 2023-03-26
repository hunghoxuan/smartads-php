<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'TOOLS::CACHE';
$moduleTitle = 'Cache';
$moduleKey = 'TOOLS::CACHE';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

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
$key = FHtml::getRequestParam('key');
$ajax = true;

?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>

<?= FHtml::showMessage(!empty($message) ? $message : '', null) ?>
<?= FHtml::render('_menu_right') ?>

    <div class="form">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>

                        </span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Cache') ?></a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'OpCache') ?></a>
                            </li>

                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">

                                        <form id="test" method="get" action="<?= FHtml::createUrl('/tools/cache') ?>">
                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                    <h2>Cache Folders</h2>
                                                    <?php $folder = FHtml::getRootFolder() . "/backend/runtime/cache"; $arr=FHtml::listFolders($folder); echo FHtml::showToogleContent("Backend: <br/> <b>$folder</b> (" . count($arr) . " folders)", '- ' . implode('<br/>- ', $arr)); ?>
                                                    <br/>
                                                    <?php $folder = FHtml::getRootFolder() . "/frontend/runtime/cache"; $arr=FHtml::listFolders($folder); echo FHtml::showToogleContent("Frontend: <br/> <b>$folder</b> (" . count($arr) . " folders)", '- ' . implode('<br/>- ', $arr)); ?>
                                                    <br/>
                                                    <?php $folder = FHtml::getRootFolder() . "/assets"; $arr=FHtml::listFolders($folder); echo FHtml::showToogleContent("Assets: <br/> <b>$folder</b> (" . count($arr) . " folders)", '- ' . implode('<br/>- ', $arr)); ?>

                                                    <br/>
                                                    <button type="submit" name="action" value="flush" class="btn btn-danger">Refresh Cache</button>
                                                    <hr />
                                                    Get Cache Data by Key:<br/>
                                                    <input type="text" name="key" id="key"
                                                           value="<?= $key ?>"
                                                           class="form-control"/>
                                                    <br/>

                                                    <button type="submit" name="action" value="view"
                                                            class="btn btn-blue">View
                                                    </button>

                                                    <?= !empty($model) ? FHtml::var_dump($model)  : "<br/>No cached item with key: <b> $key </b>" ?>
                                                    <?php if (!empty($model)) { ?>
                                                        <button type="submit" name="action" value="remove"
                                                                class="btn btn-warning">Remove
                                                        </button>
                                                    <?php } ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <h2>Cache Component: </h2> <b><?php echo FHtml::Cache()->className() ?> </b><br/> <br/>

                                                    <?php
                                                    FHtml::var_dump(FHtml::Cache());
                                                    ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-6">
                                            OpCache Configuration:
                                            <?= function_exists('opcache_get_configuration') ? FHtml::var_dump(opcache_get_configuration()) : '<span class="error">Opcache is disabled</span>' ?>
                                           </div>
                                        <div class="col-md-6">
                                             OpCache Status :
                                            <?= function_exists('opcache_get_status') ? FHtml::var_dump(opcache_get_status(false)) : '<span class="error">Opcache is disabled. Please Enable Opcache</span>' ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
<?php if ($ajax) Pjax::end() ?>