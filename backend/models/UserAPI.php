<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "user".
 */
class UserAPI extends User
{
    public function fields()
    {
        //Customize fields to be displayed in API
        //$fields = ['id', 'code', 'name', 'username', 'image', 'overview', 'content', 'dob', 'gender', 'identity_card', 'email', 'phone', 'skype', 'address', 'city', 'organization', 'department', 'position', 'start_date', 'end_date', 'role', 'type', 'status', 'created_at', 'updated_at', ];
        $fields = parent::fields();
        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
