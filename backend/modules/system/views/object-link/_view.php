<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'ObjectLink';

$role = isset($role) ? $role : FHtml::getCurrentRole();
$action = isset($action) ? $action : FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($action) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectLink */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Object Links';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="object-link-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'object_id',
                'object_type',
                'name',
                'link_url',
                'type',
                'is_active',
                'created_date',
                'created_user',
                'application_id',
                'image',
                'description',
                'label',
                'target',
                'sort_order',
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
                    'name',
                    'link_url',
                    'type',
                    'is_active',
                    'created_date',
                    'created_user',
                    'application_id',
                    'image',
                    'description',
                    'label',
                    'target',
                    'sort_order',
                ],
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($ajax) Pjax::end() ?>
