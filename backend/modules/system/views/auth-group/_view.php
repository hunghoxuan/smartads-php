<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'AuthGroup';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\AuthGroup */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Auth Groups';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable-pjax'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="auth-group-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'name',
                'is_active',
                'created_date',
                'modified_date',
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
                'name',
                'is_active',
                'created_date',
                'modified_date',
                'application_id',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

