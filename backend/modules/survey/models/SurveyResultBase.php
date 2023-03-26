<?php

namespace backend\modules\survey\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "survey_result".
 *
 * @property integer $id
 * @property integer $survey_id
 * @property integer $question_id
 * @property integer $customer_id
 * @property integer $customer_info
 * @property string $transaction_id
 * @property string $comment
 * @property string $answer
 * @property string $branch_id
 * @property string $employee_id
 * @property string $created_date
 * @property string $application_id
 * @property string $ime
 */
class SurveyResultBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'survey_result';

    public static function tableName()
    {
        return 'survey_result';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_id', 'question_id', 'customer_id', 'customer_info', 'transaction_id', 'comment', 'answer', 'branch_id', 'employee_id', 'created_date', 'application_id', 'ime'], 'filter', 'filter' => 'trim'],
            [['survey_id', 'question_id', 'customer_id', 'customer_info'], 'integer'],
            [['answer'], 'required'],
            [['created_date'], 'safe'],
            [['transaction_id', 'answer', 'branch_id', 'employee_id', 'application_id', 'ime'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SurveyResult', 'ID'),
            'survey_id' => FHtml::t('SurveyResult', 'Survey ID'),
            'question_id' => FHtml::t('SurveyResult', 'Question ID'),
            'customer_id' => FHtml::t('SurveyResult', 'Customer ID'),
            'customer_info' => FHtml::t('SurveyResult', 'Customer Info'),
            'transaction_id' => FHtml::t('SurveyResult', 'Transaction ID'),
            'comment' => FHtml::t('SurveyResult', 'Comment'),
            'answer' => FHtml::t('SurveyResult', 'Answer'),
            'branch_id' => FHtml::t('SurveyResult', 'Branch ID'),
            'employee_id' => FHtml::t('SurveyResult', 'Employee ID'),
            'created_date' => FHtml::t('SurveyResult', 'Created Date'),
            'application_id' => FHtml::t('SurveyResult', 'Application ID'),
            'ime' => FHtml::t('SurveyResult', 'Ime'),
        ];
    }


}