<?php

/**



 * This is the customized model class for table "Settings".
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';

$this->title = FHtml::t('common', $moduleTitle);

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
$model = isset($model) ? $model : \backend\models\Settings::getInstance();
$canEdit = isset($canEdit) ? $canEdit : FHtml::isRoleAdmin();

?>
<div class="hidden-print">
    <?= FHtml::render('_settings', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'model' => $model,
        'canEdit' => $canEdit

    ]) ?></div>