<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'ObjectCalendar';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectCalendar */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Object Calendars';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="object-calendar-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'object_id',
                'object_type',
                'color',
                'title',
                'start_date',
                'end_date',
                'all_day',
                'status',
                'link_url',
                'type',
                'created_user',
                'created_date',
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
                    'color',
                    'title',
                    'start_date',
                    'end_date',
                    'all_day',
                    'status',
                    'link_url',
                    'type',
                    'created_user',
                    'created_date',
                    'application_id',
                ],
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($ajax) Pjax::end() ?>
