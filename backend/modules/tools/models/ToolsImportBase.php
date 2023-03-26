<?php

namespace backend\modules\tools\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "tools_import".
 *

 * @property string $id
 * @property string $name
 * @property string $file
 * @property string $sheet_name
 * @property integer $first_row
 * @property integer $last_row
 * @property string $object_type
 * @property string $key_columns
 * @property string $columns
 * @property string $default_values
 * @property string $override_type
 * @property string $type
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ToolsImportBase extends BaseModel //\yii\db\ActiveRecord
{
    const OVERRIDE_TYPE_OVERRIDE = 'override';
    const OVERRIDE_TYPE_DELETE = 'delete';
    const OVERRIDE_TYPE_ADD = 'add';

    /**
    * @inheritdoc
    */
    public $tableName = 'tools_import';

    public static function tableName()
    {
        return 'tools_import';
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
        
            [['id', 'name', 'file', 'sheet_name', 'first_row', 'last_row', 'object_type', 'key_columns', 'columns', 'default_values', 'override_type', 'type', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['name'], 'required'],
            [['first_row', 'last_row'], 'integer'],
            [['columns', 'default_values'], 'string'],
            [['created_date'], 'safe'],
            [['name', 'file', 'sheet_name', 'object_type'], 'string', 'max' => 255],
            [['key_columns'], 'string', 'max' => 2000],
            [['override_type', 'type', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('ToolsImport', 'ID'),
                    'name' => FHtml::t('ToolsImport', 'Name'),
                    'file' => FHtml::t('ToolsImport', 'File'),
                    'sheet_name' => FHtml::t('ToolsImport', 'Sheet Name'),
                    'first_row' => FHtml::t('ToolsImport', 'First Row'),
                    'last_row' => FHtml::t('ToolsImport', 'Last Row'),
                    'object_type' => FHtml::t('ToolsImport', 'Object Type'),
                    'key_columns' => FHtml::t('ToolsImport', 'Key Columns'),
                    'columns' => FHtml::t('ToolsImport', 'Columns'),
                    'default_values' => FHtml::t('ToolsImport', 'Default Values'),
                    'override_type' => FHtml::t('ToolsImport', 'Override Type'),
                    'type' => FHtml::t('ToolsImport', 'Type'),
                    'created_date' => FHtml::t('ToolsImport', 'Created Date'),
                    'created_user' => FHtml::t('ToolsImport', 'Created User'),
                    'application_id' => FHtml::t('ToolsImport', 'Application ID'),
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
        $i18n->translations['ToolsImport*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/tools/messages',
            'fileMap' => [
                'ToolsImport' => 'ToolsImport.php',
            ],
        ];
    }




}
