<?php

use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\app\models\AppVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'AppVersion';
$moduleTitle = 'App Version';
$moduleKey = 'app-version';
$object_type = 'app-version';

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

?>
<?= FHtml::render($gridControl, $viewType, [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'viewType' => $viewType
]) ?>