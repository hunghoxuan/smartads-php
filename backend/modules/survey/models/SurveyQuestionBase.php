<?php

namespace backend\modules\survey\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "survey_question".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $content
 * @property string $type
 * @property integer $allow_comment
 * @property integer $timeout
 * @property string $hint
 * @property string $answers
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class SurveyQuestionBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'survey_question';

    public static function tableName()
    {
        return 'survey_question';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'image', 'content', 'type', 'allow_comment', 'timeout', 'hint', 'answers', 'sort_order', 'is_active', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name', 'content'], 'required'],
            [['content', 'hint'], 'string'],
            [['allow_comment', 'timeout', 'sort_order', 'is_active'], 'integer'],
            [['created_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 300],
            [['type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['answers'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SurveyQuestion', 'ID'),
            'name' => FHtml::t('SurveyQuestion', 'Name'),
            'image' => FHtml::t('SurveyQuestion', 'Image'),
            'content' => FHtml::t('SurveyQuestion', 'Content'),
            'type' => FHtml::t('SurveyQuestion', 'Type'),
            'allow_comment' => FHtml::t('SurveyQuestion', 'Allow Comment'),
            'timeout' => FHtml::t('SurveyQuestion', 'Timeout'),
            'hint' => FHtml::t('SurveyQuestion', 'Hint'),
            'answers' => FHtml::t('SurveyQuestion', 'Answers'),
            'sort_order' => FHtml::t('SurveyQuestion', 'Sort Order'),
            'is_active' => FHtml::t('SurveyQuestion', 'Is Active'),
            'created_date' => FHtml::t('SurveyQuestion', 'Created Date'),
            'created_user' => FHtml::t('SurveyQuestion', 'Created User'),
            'application_id' => FHtml::t('SurveyQuestion', 'Application ID'),
        ];
    }


}