<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "smartscreen_file".
 */
class SmartscreenFileAPI extends SmartscreenFileSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'object_id', 'file', 'description', 'file_kind', 'file_size', 'file_duration', 'is_active',];

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
