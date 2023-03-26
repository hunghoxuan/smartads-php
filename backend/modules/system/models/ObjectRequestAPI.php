<?php

namespace backend\modules\system\models;

use common\base\BaseAPIObject;

/**
 * This is the model class for table "object_request".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $name
 * @property string $email
 * @property string $type
 * @property integer $is_active
 * @property integer $user_id
 * @property string $user_type
 * @property string $user_role
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */

class ObjectRequestAPI extends BaseAPIObject
{
    const TYPE_VIP = 'vip';
    const TYPE_MODERATOR = 'moderator';
    const TYPE_UNLOCK = 'unlock';

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }
}