<?php

namespace backend\modules\system\models;

use common\base\BaseAPIObject;

/**
 * This is the model class for table "object_message".
 *
 * @property integer $id
 * @property string $object_id
 * @property string $object_type
 * @property string $title
 * @property string $message
 * @property string $method
 * @property string $send_date
 * @property integer $sender_id
 * @property string $sender_type
 * @property string $type
 * @property string $status
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectMessageAPI extends BaseAPIObject
{
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const TYPE_NOTIFY = 'notify';
    const TYPE_WARNING = 'warning';
    const TYPE_BIRTHDAY = 'birthday';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_REMIND = 'remind';
    const METHOD_PUSH = 'push';
    const METHOD_EMAIL = 'email';
    const METHOD_SMS = 'sms';

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function getApiFields() {
        $fields = [
            'id',
            'object_id',
            'object_type',
            'title',
            'message',
            'method',
            'send_date',
            'sender_id',
            'sender_type',
            'type',
            'status'
        ];
        return $fields;
    }
}