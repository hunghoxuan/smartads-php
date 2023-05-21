<?php

/*
 * This is the customized model class for table "MediaFile".
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\media\models\MediaFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'MediaFile';
$moduleTitle = 'Media File';
$moduleKey = 'media-file';

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

?>
<div class="hidden-print">
    <?= FHtml::buildAdminToolbar(str_replace('-', '_', $moduleKey)) ?> <?= FHtml::render('_index', FHtml::getRequestParam('view'), [
                                                                            'dataProvider' => $dataProvider,
                                                                            'searchModel' => $searchModel,
                                                                        ]) ?></div>

<div class="visible-print">
    <?= $this->render('_index_print', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel
    ]) ?></div>