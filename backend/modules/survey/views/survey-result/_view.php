<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'SurveyResult';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\survey\models\SurveyResult */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Survey Results';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="survey-result-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'survey_id',
                'question_id',
                'customer_id',
                'customer_info',
                'transaction_id',
                'comment',
                'answer',
                'branch_id',
                'employee_id',
                'created_date',
                'application_id',
                'ime',
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
                    'survey_id',
                    'question_id',
                    'customer_id',
                    'customer_info',
                    'transaction_id',
                    'comment',
                    'answer',
                    'branch_id',
                    'employee_id',
                    'created_date',
                    'application_id',
                    'ime',
                ],
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($ajax) Pjax::end() ?>
