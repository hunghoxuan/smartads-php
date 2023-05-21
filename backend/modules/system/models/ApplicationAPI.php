<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "application".
 */
class ApplicationAPI extends Application
{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'logo', 'code', 'name', 'description', 'keywords', 'note', 'lang', 'modules', 'storage_max', 'storage_current', 'address', 'map', 'website', 'email', 'phone', 'fax', 'chat', 'facebook', 'twitter', 'google', 'youtube', 'copyright', 'terms_of_service', 'profile', 'privacy_policy', 'is_active', 'type', 'status', 'owner_id',];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
