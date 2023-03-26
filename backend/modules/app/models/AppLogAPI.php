<?php

namespace backend\modules\app\models;

use common\base\BaseAPIObject;

/**
 * @property string $id
 * @property string $user_id
 * @property string $action
 * @property string $note
 * @property string $tracking_time
 * @property string $status
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppLogAPI extends BaseAPIObject
{
    public static function tableName()
    {
        return 'app_log';
    }

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function rules()
    {
        return [];
    }
}