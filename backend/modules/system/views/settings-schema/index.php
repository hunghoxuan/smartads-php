<?php

/**
 *
 ***
 * This is the customized model class for table "SettingsSchema".
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsSchemaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SettingsSchema';
$moduleTitle = 'Settings Schema';
$moduleKey = 'settings-schema';

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

?>
<div class="hidden-print">
    <?= FHtml::render(FHtml::settingPageView('Index', '_index'), FHtml::getRequestParam('view'), [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]) ?></div>

<div class="visible-print">
    <?= $this->render($folder . '_index_print', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel
    ]) ?></div>