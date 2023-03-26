<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "app_file".
 *
 * @property integer $id
 * @property string $file_name
 * @property integer $file_size
 * @property string $user_id
 * @property string $ime
 * @property string $status
 * @property string $download_time
 * @property string $created_date
 * @property string $application_id
 */
class AppFileBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'app_file';

    public static function tableName()
    {
        return 'app_file';
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
            [['id', 'file_name', 'file_size', 'user_id', 'ime', 'status', 'download_time', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['file_name', 'application_id'], 'required'],
            [['file_size'], 'integer'],
            [['download_time', 'created_date'], 'safe'],
            [['file_name', 'ime'], 'string', 'max' => 500],
            [['user_id', 'status', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppFile', 'ID'),
            'file_name' => FHtml::t('AppFile', 'File Name'),
            'file_size' => FHtml::t('AppFile', 'File Size'),
            'user_id' => FHtml::t('AppFile', 'User ID'),
            'ime' => FHtml::t('AppFile', 'Ime'),
            'status' => FHtml::t('AppFile', 'Status'),
            'download_time' => FHtml::t('AppFile', 'Download Time'),
            'created_date' => FHtml::t('AppFile', 'Created Date'),
            'application_id' => FHtml::t('AppFile', 'Application ID'),
        ];
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['AppFile*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/app/messages',
            'fileMap' => [
                'AppFile' => 'AppFile.php',
            ],
        ];
    }
}