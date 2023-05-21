<?php

/**
 *
 ***
 * This is the customized model class for table "AppUserTransaction".
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\app\models\AppUserTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'AppUserTransaction';
$moduleTitle = 'App User Transaction';
$moduleKey = 'app-user-transaction';

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
    <?= FHtml::buildAdminToolbar(str_replace('-', '_', $moduleKey)) ?>
    <?= FHtml::render(FHtml::settingPageView('Index', '_index'), FHtml::getRequestParam('view'), [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]) ?></div>

<div class="visible-print">
    <?= $this->render(FHtml::settingPageView('Index Print', '_index_print'), [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel
    ]) ?></div>