<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
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
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
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
