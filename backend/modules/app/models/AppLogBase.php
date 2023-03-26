<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "app_log".
 *
 * @property string $id
 * @property string $user_id
 * @property string $action
 * @property string $note
 * @property string $tracking_time
 * @property string $status
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppLogBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const ACTION_REGISTER = 'register';
    const ACTION_LOGIN = 'login';
    const ACTION_PURCHASE = 'purchase';
    const ACTION_FEEDBACK = 'feedback';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';
    const STATUS_BLOCK = 'block';

    /**
     * @inheritdoc
     */
    public $tableName = 'app_log';

    public static function tableName()
    {
        return 'app_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'action', 'note', 'tracking_time', 'status', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['user_id'], 'required'],
            [['note'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['user_id', 'action', 'status', 'application_id'], 'string', 'max' => 100],
            [['tracking_time'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppLog', 'ID'),
            'user_id' => FHtml::t('AppLog', 'User ID'),
            'action' => FHtml::t('AppLog', 'Action'),
            'note' => FHtml::t('AppLog', 'Note'),
            'tracking_time' => FHtml::t('AppLog', 'Tracking Time'),
            'status' => FHtml::t('AppLog', 'Status'),
            'created_date' => FHtml::t('AppLog', 'Created Date'),
            'modified_date' => FHtml::t('AppLog', 'Modified Date'),
            'application_id' => FHtml::t('AppLog', 'Application ID'),
        ];
    }


}