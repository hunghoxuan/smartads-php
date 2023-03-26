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
$moduleTitle = 'Copy';
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
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Copy') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">

                                    <form id="test" method="POST" action="<?= FHtml::createUrl('/tools/copy') ?>">
                                            <div class="col-md-12">
                                                <div class="">
                                                    

                                                    Copy to:<br/>
                                                    <input type="text" name="key" id="key"
                                                                                 value=""
                                                                                 class="form-control" placeholder="Enter name project" />
                                                    <br/>
                                                    <h4>Choose folder copy: </h4>
                                                    <div role="tabpanel">
                                                        <!-- Nav tabs -->
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li role="presentation" class="active">
                                                                <a href="#all" aria-controls="all" role="tab" data-toggle="tab">all</a>
                                                            </li>
                                                            <li role="presentation" class="">
                                                                <a href="#common" aria-controls="common" role="tab" data-toggle="tab">common</a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a href="#backend" aria-controls="backend" role="backend" data-toggle="tab">backend</a>
                                                            </li>
                                                        </ul>
                                                        
                                                        <!-- Tab panes -->
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane" id="all">
                                                                <div class="form-group">
                                                                </div>
                                                            </div>

                                                            <div role="tabpanel" class="tab-pane" id="common">
                                                                <div class="form-group">
                                                                    <select multiple class="form-control" id="" name="choose_folder[]">
                                                                        <option value="common/actions">actions</option>
                                                                        <option value="common/base">base</option>
                                                                        <option value="common/components">components</option>
                                                                        <option value="common/controllers">controllers</option>
                                                                        <option value="common/messages">messages</option>
                                                                        <option value="common/models">models</option>
                                                                        <option value="common/widgets">widgets</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div role="tabpanel" class="tab-pane" id="backend">
                                                                <div class="form-group">
                                                                    <select multiple class="form-control" id="" name="choose_folder[]">
                                                                        <option value="backend/actions">actions</option>
                                                                        <option value="backend/base">base</option>
                                                                        <option value="backend/components">components</option>
                                                                        <option value="backend/controllers">controllers</option>
                                                                        <option value="backend/messages">messages</option>
                                                                        <option value="backend/models">models</option>
                                                                        <option value="backend/widgets">widgets</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <button type="submit" name="copy" name="action" value="copy" class="btn btn-danger">Copy</button>
                                                </div>
                                             </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">

                                        </div>
                                    </div>
                                    <ul>

                                    </ul>
                                    For direct question and support, please send email to the author of application: <a
                                            href="mailto:<?= FHtml::getAuthorEmail() ?>"><?= FHtml::getAuthor() ?></a>
                                    (<?= FHtml::getAuthorEmail() ?>) <br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php if ($ajax) Pjax::end() ?>