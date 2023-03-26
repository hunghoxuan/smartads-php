<?php

namespace backend\modules\app\models;

use Braintree\Base;
use common\base\BaseAPIObject;

/**
 * This is the model class for table "app_device".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $imei
 * @property string $token
 * @property string $type
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppDeviceAPI extends BaseAPIObject
{
    const TYPE_ANDROID = 'android';
    const TYPE_IOS = 'ios';

    const COLUMNS_API = [
        'id',
        'user_id',
        'imei',
        'token',
        'type',
        'is_active'
    ];

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function getApiFields()
    {
        $fields = [
            'id',
            'user_id',
            'imei',
            'token',
            'type',
            'is_active'
        ];
        return $fields;
    }
}