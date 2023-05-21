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
class ToolsImportAPI extends ToolsImport
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'name', 'file', 'first_row', 'last_row', 'object_type', 'key_columns', 'columns', 'default_values', 'override_type', 'type',];

    public function fields()
    {
        $fields = $this::COLUMNS_API;

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
