<?php

namespace backend\modules\tools\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "tools_copy".
 *

 * @property string $id
 * @property string $name
 * @property string $folders
 * @property string $files
 * @property string $description
 * @property string $created_date
 * @property string $modified_date
 * @property integer $created_user
 * @property string $application_id
 */
class ToolsCopyBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'tools_copy';

    public static function tableName()
    {
        return 'tools_copy';
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

            [['id', 'name', 'folders', 'files', 'description', 'created_date', 'modified_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],

            // [['folders', 'files'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['created_user'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['description', 'application_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ToolsCopy', 'ID'),
            'name' => FHtml::t('ToolsCopy', 'Name'),
            'folders' => FHtml::t('ToolsCopy', 'Folders'),
            'files' => FHtml::t('ToolsCopy', 'Files'),
            'description' => FHtml::t('ToolsCopy', 'Description'),
            'created_date' => FHtml::t('ToolsCopy', 'Created Date'),
            'modified_date' => FHtml::t('ToolsCopy', 'Modified Date'),
            'created_user' => FHtml::t('ToolsCopy', 'Created User'),
            'application_id' => FHtml::t('ToolsCopy', 'Application ID'),
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
        $i18n->translations['ToolsCopy*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/tools/messages',
            'fileMap' => [
                'ToolsCopy' => 'ToolsCopy.php',
            ],
        ];
    }
}
