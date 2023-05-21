<?php

namespace backend\modules\tools\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "tools_import".
 */
class ToolsImport extends ToolsImportBase //\yii\db\ActiveRecord
{
    const LOOKUP = [
        'override_type' => [
            ['id' => ToolsImport::OVERRIDE_TYPE_OVERRIDE, 'name' => 'override'],
            ['id' => ToolsImport::OVERRIDE_TYPE_DELETE, 'name' => 'delete'],
            ['id' => ToolsImport::OVERRIDE_TYPE_ADD, 'name' => 'add'],
        ],
    ];

    const COLUMNS_UPLOAD = ['file',];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column)
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public function fields()
    {
        $fields = array_merge(parent::fields(), self::OBJECTS_RELATED);

        foreach (self::COLUMNS_UPLOAD as $field) {
            $this->{$field} = FHtml::getFileURL($this->{$field}, $this->getTableName());
        }
        return $fields;
    }




    public function prepareCustomFields()
    {
        parent::prepareCustomFields();
    }


    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }
}
