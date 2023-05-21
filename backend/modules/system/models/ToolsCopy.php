<?php

namespace backend\modules\system\models;

use common\base\BasePHPObject;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "tools_copy".
 */
class ToolsCopy extends BasePHPObject
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    /**
     * @inheritdoc
     */
    public $tableName = 'tools_copy';

    public static function tableName()
    {
        return 'tools_copy';
    }

    public function fields()
    {
        return ['id', 'name', 'folders', 'files', 'description', 'created_date', 'modified_date', 'created_user', 'application_id'];
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDbType()
    {
        return FHtml::DB_TYPE_PHP;
    }
}
