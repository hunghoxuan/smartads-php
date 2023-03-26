<?php

namespace backend\modules\survey\actions;

use backend\modules\app\models\AppDeviceAPI;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use backend\modules\survey\models\SurveyResult;
use backend\modules\survey\Survey;
use common\components\FApi;
use common\components\FConstant;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use common\actions\BaseApiAction;
use common\components\FHtml;


class SurveyResultAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';

        if (empty($ime)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 205]);
        }

        $question_id = FHtml::getRequestParam('question_id');
        $survey_id = FHtml::getRequestParam('survey_id');
        $answer = FHtml::getRequestParam('answer');
        $comment = FHtml::getRequestParam('comment');
        $transaction_id = FHtml::getRequestParam('transaction_id');
        $vote_device_id = FHtml::getRequestParam('vote_device_id');
        $vote_current_service = FHtml::getRequestParam('vote_current_service');
        $customer_id = FHtml::getRequestParam('customer_id');
        $customer_info = FHtml::getRequestParam('customer_info');
        $employee_id = FHtml::getRequestParam('employee_id');
        $branch_id = FHtml::getRequestParam('branch_id');

        $model = SurveyResult::findOne(['survey_id' => $survey_id, $question_id => $question_id]);
        if (isset($model))
            return FApi::getOutputForAPI('', FConstant::ERROR, "Result is dupblicated", ['code' => 205]);

        $arr = FHtml::decode($answer);
        $result = '';
        if (is_array($arr) && !empty($arr)) {
            foreach ($arr as $item) {
                $question_id = FHtml::getFieldValue($item, ['question_id', 'id']);
                $answer = FHtml::getFieldValue($item, ['answer', 'answers']);
                $comment = FHtml::getFieldValue($item, ['comment', 'comment']);

                if (empty($question_id))
                    continue;

                $model = new SurveyResult();
                $model->survey_id = $survey_id;
                $model->question_id = $question_id;
                $model->answer = $answer;
                $model->comment = $comment;
                $model->ime = $ime;
                $model->transaction_id = $transaction_id;
                $model->application_id = FHtml::currentApplicationId();

                $result = $model->save();
            }
        } else {
            $model = new SurveyResult();
            $model->survey_id = $survey_id;
            $model->question_id = $question_id;
            $model->answer = $answer;
            $model->comment = $comment;
            $model->ime = $ime;
            $model->transaction_id = $transaction_id;
            $model->application_id = FHtml::currentApplicationId();

            $result = $model->save();
        }

        if (is_string($result)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, $result, ['code' => 205]);
        }

        $result = FApi::getOutputForAPI($result, FConstant::SUCCESS, 'OK', ['code' => 200]);

        return $result;
    }
}
