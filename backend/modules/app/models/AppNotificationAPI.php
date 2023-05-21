<?php

namespace backend\modules\app\models;

/**



 * This is the customized model class for table "app_notification".
 */
class AppNotificationAPI extends AppNotification
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'message', 'action', 'params', 'sent_type', 'sent_date', 'receiver_count', 'receiver_users',];

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
