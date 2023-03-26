<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = '<?=StringHelper::basename($generator->modelClass)?>';
$moduleTitle = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$object_type = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';

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
<?php echo "<?= FHtml::render(\$gridControl, \$viewType, [
    'dataProvider' => \$dataProvider,
    'searchModel' => \$searchModel,
    'viewType' => \$viewType
]) ?>"; ?>
