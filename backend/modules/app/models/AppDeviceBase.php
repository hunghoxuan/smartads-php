<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "app_device".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $imei
 * @property string $token
 * @property string $type
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppDeviceBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_ANDROID = 'android';
    const TYPE_IOS = 'ios';

    /**
     * @inheritdoc
     */
    public $tableName = 'app_device';

    public static function tableName()
    {
        return 'app_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'imei', 'token', 'type', 'is_active', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['user_id', 'is_active'], 'integer'],
            [['imei', 'token', 'type', 'is_active'], 'required'],
            [['created_date', 'modified_date'], 'safe'],
            [['imei', 'token'], 'string', 'max' => 255],
            [['type', 'application_id'], 'string', 'max' => 100],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppDevice', 'ID'),
            'user_id' => FHtml::t('AppDevice', 'User ID'),
            'imei' => FHtml::t('AppDevice', 'Imei'),
            'token' => FHtml::t('AppDevice', 'Token'),
            'type' => FHtml::t('AppDevice', 'Type'),
            'is_active' => FHtml::t('AppDevice', 'Is Active'),
            'created_date' => FHtml::t('AppDevice', 'Created Date'),
            'modified_date' => FHtml::t('AppDevice', 'Modified Date'),
            'application_id' => FHtml::t('AppDevice', 'Application ID'),
        ];
    }


}