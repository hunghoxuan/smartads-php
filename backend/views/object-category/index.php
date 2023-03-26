<?php

use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\models\models\ObjectCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';

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
$viewType = FHtml::getRequestParam('view');
$gridControl = FHtml::settingPageView('_index');
$object_type_array = isset($object_type_array) ? $object_type_array : \backend\models\ObjectCategory::getLookupArray('#object_type');
$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');
?>
<?= FHtml::render($gridControl, $viewType, [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'viewType' => $viewType,
    'object_type' => $object_type,
    'object_type_array' => $object_type_array
]) ?>