<?php

namespace backend\modules\survey;

use backend\models\AuthMenu;
use backend\modules\survey\models\SurveyQuestion;
use backend\modules\survey\models\SurveyQuestionAPI;
use common\components\FHtml;
use yii\base\Module;

/**
 * api module definition class
 */
class Survey extends Module
{
    const FIELDS_GROUP = [ //table.column
    ];
    const LOOKUP = [    // 'table.column' => array(), 'table.column' => 'table1.column1'
        'survey_question.type' => ['single', 'multiple', 'text']
    ];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\survey\controllers';

    public static function getLookupArray($column = '') {
        if (key_exists($column, self::LOOKUP)) {
            $data = self::LOOKUP[$column];

            $data = FHtml::getComboArray($data);

            return $data;
        }

        return [];
    }

    public static function createModuleMenu($menu = ['survey*'])
    {
        $controller = FHtml::currentController();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Survey',
            'glyphicon glyphicon-th',
            FHtml::isInArray($controller, $menu),
            [],
            [
                !FHtml::isInArray('survey', $menu) ? null : AuthMenu::menuItem (
                    '/survey/survey/index',
                    'Survey',
                    'glyphicon glyphicon-cog',
                    $controller == 'survey',
                    []
                ),
                !FHtml::isInArray('survey-question', $menu) ? null : AuthMenu::menuItem (
                    '/survey/survey-question/index',
                    'Questions',
                    'glyphicon glyphicon-cog',
                    $controller == 'survey-question'
                ),
                !FHtml::isInArray('survey-result', $menu) ? null : AuthMenu::menuItem (
                    '/survey/survey-result/index',
                    'Result',
                    'glyphicon glyphicon-cog',
                    $controller == 'survey-result'
                )
            ]
        );

        return $menu;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public static function getSurveyQuestions($ime = '', $date = '',  $channel_id = '') {
        $condition = [];
        $models = SurveyQuestionAPI::findAll($condition);
        return $models;
    }

    public static function getSurveyFiles($surveys) {
        return [];
    }

    public static function prepareQuestionAnswers($answers) {
        $answers1 = FHtml::decode($answers);
        //FHtml::var_dump($answers); FHtml::var_dump($answers1); die;
        if (!is_array($answers1))
            return [];
        
        $answers = [];
        foreach ($answers1 as $i => $answer) {
            if (is_string($answer))
                $answers[] = ['key' => $i + 1, 'content' => $answer];
            elseif (is_array($answer) && !key_exists('key', $answer)) {
                $answer['key'] = $i + 1;
                $answers[] = $answer;
            }
            elseif (is_array($answer) && key_exists('key', $answer) && empty($answer['key'])) {
                $answer['key'] = $i + 1;
                $answers[] = $answer;
            } else {
                $answers[] = $answer;
            }

        }
        return $answers;
    }
}
