<?php

namespace backend\modules\survey\actions;

use backend\modules\app\models\AppDeviceAPI;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use backend\modules\survey\models\SurveyAPI;
use backend\modules\survey\Survey;
use common\components\FApi;
use common\components\FConstant;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use common\actions\BaseApiAction;
use common\components\FHtml;


class SurveyQuestionsAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : null;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
        $channel_id = isset($_REQUEST['channel_id']) ? $_REQUEST['channel_id'] : '';
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : -1;

        $survey_id = FHtml::getRequestParam('survey_id');
        $survey_name = '';


        $questions = Survey::getSurveyQuestions($ime, $date, $channel_id);
        if (is_string($questions)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, $questions, ['code' => 205]);
        }

        $result = new SurveyAPI();
        $result->id = '';
        $result->name = FHtml::t('common', 'Customer Survey');
        $result->questions = $questions;


        $files = Survey::getSurveyFiles($result);
        //$files = [];

        if (is_string($result)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, $result, ['code' => 205]);
        }

        $result = FApi::getOutputForAPI($result, FConstant::SUCCESS, 'OK', ['code' => 200]);

//        $result['survey_name'] = $survey_name;
//
//        $result['survey_id'] = $survey_id;

        $result['start_time'] = $start_time;

        $result['download_files'] = $files;

        $result['download_time'] = "";

        return $result;
    }
}
