<?php
use yii\helpers\Html;
use common\components\FHtml;


$currentRole = FHtml::getCurrentRole();
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$canCreate = true;

$moduleName = 'ObjectType';
$moduleTitle = 'Object Type';
$moduleKey = 'object-type';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole('', 'create', $currentRole)) {
    $controlName = $folder . '_form_3cols';
} else {
    $controlName = $folder . '_view';
    $canCreate = false;
}


/* @var $this yii\web\View */
/* @var $model backend\models\ObjectType */

?>
<div class="object-type-create">
    <?php if ($canCreate === true) {
        echo $this->render($controlName, [
            'model' => $model,
        ]);
    } else { ?>
        <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);
    } ?>
</div>
