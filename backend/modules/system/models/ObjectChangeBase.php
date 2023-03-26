<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_change".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $name
 * @property string $content
 * @property string $old_value
 * @property string $new_value
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectChangeBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_change';

    public static function tableName()
    {
        return 'object_change';
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
            [['id', 'object_id', 'object_type', 'name', 'content', 'old_value', 'new_value', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id', 'object_type', 'name', 'content', 'old_value', 'new_value', 'created_date', 'created_user', 'application_id'], 'required'],
            [['object_id'], 'integer'],
            [['content', 'old_value', 'new_value'], 'string'],
            [['created_date'], 'safe'],
            [['object_type', 'name', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectChange', 'ID'),
            'object_id' => FHtml::t('ObjectChange', 'Object ID'),
            'object_type' => FHtml::t('ObjectChange', 'Object Type'),
            'name' => FHtml::t('ObjectChange', 'Name'),
            'content' => FHtml::t('ObjectChange', 'Content'),
            'old_value' => FHtml::t('ObjectChange', 'Old Value'),
            'new_value' => FHtml::t('ObjectChange', 'New Value'),
            'created_date' => FHtml::t('ObjectChange', 'Created Date'),
            'created_user' => FHtml::t('ObjectChange', 'Created User'),
            'application_id' => FHtml::t('ObjectChange', 'Application ID'),
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
        $i18n->translations['ObjectChange*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/system/messages',
            'fileMap' => [
                'ObjectChange' => 'ObjectChange.php',
            ],
        ];
    }
}