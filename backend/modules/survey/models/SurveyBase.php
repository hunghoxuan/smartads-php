<?php

namespace backend\modules\survey\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "survey".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $date_start
 * @property string $date_end
 * @property integer $is_active
 * @property string $type
 * @property string $status
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class SurveyBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'survey';

    public static function tableName()
    {
        return 'survey';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'description', 'date_start', 'date_end', 'is_active', 'type', 'status', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [['date_start', 'date_end', 'created_date'], 'safe'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['type', 'status', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('Survey', 'ID'),
            'name' => FHtml::t('Survey', 'Name'),
            'description' => FHtml::t('Survey', 'Description'),
            'date_start' => FHtml::t('Survey', 'Date Start'),
            'date_end' => FHtml::t('Survey', 'Date End'),
            'is_active' => FHtml::t('Survey', 'Is Active'),
            'type' => FHtml::t('Survey', 'Type'),
            'status' => FHtml::t('Survey', 'Status'),
            'created_date' => FHtml::t('Survey', 'Created Date'),
            'created_user' => FHtml::t('Survey', 'Created User'),
            'application_id' => FHtml::t('Survey', 'Application ID'),
        ];
    }


}