<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'AppUserDevice';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUserDevice */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'App User Devices';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="app-user-device-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'user_id',
                'ime',
                'gcm_id',
                'type',
                'status',
    ],
    ]) ?>
</div>
<?php } else { ?>

        <div class="row" style="padding: 20px">
            <div class="col-md-12" style="background-color: white; padding: 20px">
                <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                                'id',
                'user_id',
                'ime',
                'gcm_id',
                'type',
                'status',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

