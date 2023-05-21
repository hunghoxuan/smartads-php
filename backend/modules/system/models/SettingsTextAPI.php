<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "settings_text".
 */
class SettingsTextAPI extends SettingsText
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'group', 'name', 'lang', 'content', 'is_active',];

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
