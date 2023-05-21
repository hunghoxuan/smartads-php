<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_type".
 */
class ObjectTypeAPI extends ObjectType
{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = parent::fields();

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
