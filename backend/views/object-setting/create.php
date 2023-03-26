<?php
use yii\helpers\Html;
use common\components\FHtml;


$currentRole = FHtml::getCurrentRole();
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$canCreate = true;

$moduleName = 'ObjectSetting';
$moduleTitle = 'Object Setting';
$moduleKey = 'object-setting';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');

if (FHtml::isInRole('', 'create', $currentRole))
{
    $controlName = $folder.'_form1';
}
else
{
    $controlName = $folder.'_view';
    $canCreate = false;
}


/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */

?>
<div class="object-setting-create">
    <?php if($canCreate === true) { echo  $this->render($controlName, [
    'model' => $model,
    ]);} else { ?>
    <?=  Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);} ?>
</div>
