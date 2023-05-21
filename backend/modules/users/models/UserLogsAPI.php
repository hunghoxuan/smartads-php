<?php

namespace backend\modules\users\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "user_logs".
 */
class UserLogsAPI extends UserLogs
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'log_date', 'user_id', 'action', 'object_type', 'object_id', 'link_url', 'ip_address', 'duration',];

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
