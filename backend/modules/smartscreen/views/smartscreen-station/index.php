<?php
/**
* Developed by Hung Ho (Steve): hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SmartscreenStation".
*/

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenStationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SmartscreenStation';
$moduleTitle = 'Smartscreen Station';
$moduleKey = 'smartscreen-station';

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

?>
<div class="">
     <?= FHtml::render('_index', FHtml::getRequestParam('view'), [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]) ?></div>

<div class="visible-print">
<?= Html::a("<i class=\"fa fa-refresh\" aria-hidden=\"true\"></i>", FHtml::currentUrl(), ['class' => 'btn btn-lg btn-default', 'id' => 'refreshButton1']) ?>
</div>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 1000);
});
JS;
$this->registerJs($script);
?>

