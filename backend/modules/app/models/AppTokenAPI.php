<?php

namespace backend\modules\app\models;

use common\base\BaseAPIObject;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $time
 * @property integer $is_expired
 * @property string $application_id
 *
 * @property AppUserAPI $user
 *
 */
class AppTokenAPI extends BaseAPIObject
{
    public static function tableName()
    {
        return 'app_token';
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

    public function getUser()
    {
        return $this->hasOne(AppUserAPI::className(), ['id' => 'user_id']);
    }
}