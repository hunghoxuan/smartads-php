<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'AppNotification';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppNotification */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'App Notifications';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="app-notification-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'message',
                'action',
                'params',
                'sent_type',
                'sent_date',
                'receiver_count',
                'receiver_users',
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
                                'id',
                'message',
                'action',
                'params',
                'sent_type',
                'sent_date',
                'receiver_count',
                'receiver_users',
                'created_date',
                'created_user',
                'application_id',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

