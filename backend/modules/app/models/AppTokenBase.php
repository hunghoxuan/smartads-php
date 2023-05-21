<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "app_token".
 *

 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $time
 * @property integer $is_expired
 * @property string $created_user
 * @property string $created_date
 * @property string $application_id
 */
class AppTokenBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'app_token';

    public static function tableName()
    {
        return 'app_token';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'user_id', 'token', 'time', 'is_expired', 'created_user', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],

            [['user_id'], 'required'],
            [['user_id', 'is_expired'], 'integer'],
            [['created_date'], 'safe'],
            [['token', 'time', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppToken', 'ID'),
            'user_id' => FHtml::t('AppToken', 'User ID'),
            'token' => FHtml::t('AppToken', 'Token'),
            'time' => FHtml::t('AppToken', 'Time'),
            'is_expired' => FHtml::t('AppToken', 'Is Expired'),
            'created_user' => FHtml::t('AppToken', 'Created User'),
            'created_date' => FHtml::t('AppToken', 'Created Date'),
            'application_id' => FHtml::t('AppToken', 'Application ID'),
        ];
    }

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['AppToken*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/app/messages',
            'fileMap' => [
                'AppToken' => 'AppToken.php',
            ],
        ];
    }
}
