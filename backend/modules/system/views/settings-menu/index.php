<?php

/**
 *
 ***
 * This is the customized model class for table "SettingsMenu".
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SettingsMenu';
$moduleTitle = 'Cms Menu';
$moduleKey = 'settingsmenu';

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
    <div class="col-md-12">
        <div class="col-md-12">
        </div>
        <?= FHtml::render('_index', FHtml::getRequestParam('view'), [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]) ?>
    </div>
</div>