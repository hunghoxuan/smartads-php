<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;

/**
 *
 ***
 * This is the model class for table "settings_schema".
 *

 * @property integer $id
 * @property string $object_type
 * @property string $name
 * @property string $description
 * @property string $dbType
 * @property string $editor
 * @property string $lookup
 * @property string $format
 * @property string $algorithm
 * @property string $group
 * @property string $roles
 * @property integer $sort_order
 * @property integer $is_group
 * @property integer $is_column
 * @property integer $is_readonly
 * @property integer $is_active
 * @property integer $is_system
 */
class SettingsSchemaBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const DBTYPE_NUMERIC = 'numeric';
    const DBTYPE_BOOL = 'bool';
    const DBTYPE_FLOAT = 'float';
    const DBTYPE_VARCHAR = 'varchar';
    const DBTYPE_TEXT = 'text';
    const DBTYPE_DATE = 'date';
    const DBTYPE_TIME = 'time';
    const DBTYPE_DATETIME = 'datetime';
    const EDITOR_TEXT = 'text';
    const EDITOR_TEXTAREA = 'textarea';
    const EDITOR_SELECT = 'select';
    const EDITOR_NUMERIC = 'numeric';
    const EDITOR_CURRENCY = 'currency';
    const EDITOR_BOOLEAN = 'boolean';
    const EDITOR_DATE = 'date';
    const EDITOR_TIME = 'time';
    const EDITOR_DATETIME = 'datetime';
    const EDITOR_RANGE = 'range';
    const EDITOR_FILE = 'file';
    const EDITOR_IMAGE = 'image';

    // id, object_type, name, description, dbType, editor, lookup, format, algorithm, group, roles, sort_order, is_group, is_column, is_readonly, is_active, is_system
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_DBTYPE = 'dbType';
    const COLUMN_EDITOR = 'editor';
    const COLUMN_LOOKUP = 'lookup';
    const COLUMN_FORMAT = 'format';
    const COLUMN_ALGORITHM = 'algorithm';
    const COLUMN_GROUP = 'group';
    const COLUMN_ROLES = 'roles';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_IS_GROUP = 'is_group';
    const COLUMN_IS_COLUMN = 'is_column';
    const COLUMN_IS_READONLY = 'is_readonly';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_IS_SYSTEM = 'is_system';

    /**
     * @inheritdoc
     */
    public $tableName = 'settings_schema';

    public static function tableName()
    {
        return 'settings_schema';
    }



    /**
     * @inheritdoc
     * @return SettingsSchemaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsSchemaQuery(get_called_class());
    }
}
