<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'ObjectActions';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectActions */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Object Actions';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="object-actions-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [

                'object_id',
                'object_type',
                'name',
                'old_content',
                'content',
                'action',
                'is_active',
                'created_date',
                'created_user',
                'application_id',
    ],
    ]) ?>
</div>
<?php } else { ?>

        <div class="row" style="padding: 20px">
            <div class="col-md-12" style="background-color: white; padding: 20px">
                <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                'object_id',
                'object_type',
                 'showName',
                'showOldContent',
                'showContent',
                'action',

                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

