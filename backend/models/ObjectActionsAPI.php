<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_actions".
 */
class ObjectActionsAPI extends ObjectActions
{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'object_id', 'object_type', 'name', 'old_content', 'content', 'action', 'is_active',];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
