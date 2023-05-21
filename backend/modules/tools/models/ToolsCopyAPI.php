<?php

namespace backend\modules\tools\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "tools_copy".
 */
class ToolsCopyAPI extends ToolsCopySearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'name', 'folders', 'files', 'description',];

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
