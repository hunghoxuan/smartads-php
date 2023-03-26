<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'SurveyQuestion';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\survey\models\SurveyQuestion */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Survey Questions';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="survey-question-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'content',
                'type',
                'allow_comment',
                'timeout',
                'hint',
                'answers',
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
                    'name',
                    'content',
                    'type',
                    'allow_comment',
                    'timeout',
                    'hint',
                    'answers',
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
