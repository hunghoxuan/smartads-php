<?php

namespace backend\modules\users\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "user_feedback".
 */
class UserFeedbackAPI extends UserFeedback
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'user_id', 'object_id', 'object_type', 'name', 'email', 'comment', 'is_active', 'response', 'type', 'status',];

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
