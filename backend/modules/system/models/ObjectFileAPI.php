<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_file".
 */
class ObjectFileAPI extends ObjectFileSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'object_id', 'object_type', 'thumbnail', 'name', 'description', 'type', 'file', 'file_type', 'status', 'is_active', 'file_size', 'file_duration',];

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
