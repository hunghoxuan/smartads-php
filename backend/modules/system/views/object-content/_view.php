<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'ObjectContent';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectContent */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Object Contents';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="object-content-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'object_id',
                'object_type',
                'image',
                'name',
                'description',
                'content',
                'sort_order',
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
                    'id',
                    'object_id',
                    'object_type',
                    'image',
                    'name',
                    'description',
                    'content',
                    'sort_order',
                    'is_active',
                    'created_date',
                    'created_user',
                    'application_id',
                ],
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($ajax) Pjax::end() ?>
