<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "app_user_feedback".
 */
class AppUserFeedbackAPI extends AppUserFeedback
{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'user_id', 'object_id', 'object_type', 'comment', 'response', 'type', 'status',];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
