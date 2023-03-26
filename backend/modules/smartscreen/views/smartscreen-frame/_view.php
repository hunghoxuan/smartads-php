<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'SmartscreenFrame';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenFrame */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Smartscreen Frames';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="smartscreen-frame-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'name' => 'fdsfsd',
                'percentWidth',
                'percentHeight',
                'marginLeft',
                'marginTop',
                'contentLayout',
                'created_date',
                'modified_date',
                'application_id',
    ],
    ]) ?>
</div>
<?php } else { ?>

        <div class="row" style="padding: 20px">
            <div class="col-md-8" style="background-color: white; padding: 20px">
                <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                                'id',
                'name',
                'percentWidth',
                'percentHeight',
                'marginLeft',
                'marginTop',
                'contentLayout',
                'created_date',
                'modified_date',
                'application_id',
                ],
                ]) ?>

            </div>
            <div class="col-md-4">
                <?= \backend\modules\smartscreen\Smartscreen::showFramePreview($model) ?>
            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

