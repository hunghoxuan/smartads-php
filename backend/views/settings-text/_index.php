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
/* @var $searchModel backend\models\SettingsTextSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Translations';
$moduleTitle = 'Translations';
$moduleKey = 'settings-text';
$object_type = 'settings_text';

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
$canEdit = isset($canEdit) ? $canEdit : FHtml::isRoleAdmin();


?>
<div class="settings-text-index">
    <?php  if ($this->params['displayPortlet']): ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
                <div class="col-md-2">
                    <?php
                    if (FHtml::isLanguagesEnabled()) {
                        echo FHtml::showLangsMenu('black');
                        //echo FHtml::showCombo(FHtml::applicationLangsArray(), ['key' => 'language'], '', FHtml::currentLang());
                    }
                    else
                        echo "Language: " . FHtml::currentLang(); ?>
                </div>
                <div class="col-md-8">

                </div>
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
                <div class="col-md-2" style="">
                    <div class="">
                        <?php
                        echo FHtml::showCategoryList(\common\components\FConfig::getApplicationTranslationsGroups(), 'name');
                        ?>
                    </div>
                </div>
                <div class="col-md-10" style="padding-right:50px">
                    <?= FHtml::showCurrentMessages() ?>

                    <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light ' . ($viewType != 'print' ? 'bordered' : '');  ?>">
                        <?= FGridView::widget([
                        'id'=>'crud-datatable',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'filter' => null,
                        'edit_type' => $canEdit ? 'inline' : null,
                        'object_type' => $object_type,
                            'striped' => false,
                            'condensed' => true,
                            'responsive' => true,
                        'bordered' => true,
                        'toolbar' => $this->render('_toolbar.php'),
                        'columns' => require(__DIR__.'/'. ($canEdit ? '_columns.php' : '_columns_view.php')),
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
