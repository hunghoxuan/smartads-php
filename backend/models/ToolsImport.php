<?php

namespace backend\models;

use common\base\BasePHPObject;
use common\components\FConstant;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "tools_import".
 */
class ToolsImport extends BasePHPObject //\yii\db\ActiveRecord
{

    const OVERRIDE_TYPE_OVERRIDE = 'override';
    const OVERRIDE_TYPE_DELETE = 'delete';
    const OVERRIDE_TYPE_ADD = 'add';

    public $file;

    /**
     * @inheritdoc
     */
    public $tableName = 'tools_import';

    public static function tableName()
    {
        return 'tools_import';
    }

    public function fields()
    {
        return ['id', 'name', 'file', 'sheet_name', 'file_type', 'item_seperator', 'first_row', 'last_row', 'object_type', 'key_columns', 'columns', 'default_values', 'override_type', 'type', 'created_date', 'created_user', 'application_id'];
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDbType()
    {
        return FConstant::DB_TYPE_PHP;
    }

    const LOOKUP = [
        'override_type' => [
            ['id' => ToolsImport::OVERRIDE_TYPE_OVERRIDE, 'name' => 'override'],
            ['id' => ToolsImport::OVERRIDE_TYPE_DELETE, 'name' => 'delete'],
            ['id' => ToolsImport::OVERRIDE_TYPE_ADD, 'name' => 'add'],
        ],
    ];

    const FIELD_COLUMNS_KEYS = ['field', 'excel_column'];
    const FIELD_DEFAULT_VALUES_KEYS = ['field', 'value'];


    const COLUMNS_UPLOAD = ['file',];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];


    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public function beforeSave($insert)
    {
        $this->created_date = FHtml::Now();
        $this->created_user = FHtml::currentUserId();

        if (is_array($this->columns))
            $this->columns = FHtml::encode($this->columns);
        if (is_array($this->key_columns))
            $this->key_columns = FHtml::encode($this->key_columns);
        if (is_array($this->default_values))
            $this->default_values = FHtml::encode($this->default_values);

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->columns = FHtml::decode($this->columns, false, self::FIELD_COLUMNS_KEYS);
        $this->key_columns = FHtml::decode($this->key_columns, ',');
        $this->default_values = FHtml::decode($this->default_values, false, self::FIELD_DEFAULT_VALUES_KEYS);

        return parent::afterFind();
    }
}
