<?php

namespace backend\modules\app\models;

use common\base\BaseAPIObject;
use common\components\FApi;

/**
 * This is the model class for table "app_transaction".
 *
 * @property string $id
 * @property string $transaction_id
 * @property string $user_id
 * @property string $receiver_user_id
 * @property string $object_id
 * @property string $object_type
 * @property string $amount
 * @property string $currency
 * @property string $payment_method
 * @property string $note
 * @property string $time
 * @property string $action
 * @property string $type
 * @property string $status
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class AppTransactionAPI extends BaseAPIObject
{
    const PAYMENT_METHOD_POINT = 'POINT';
    const PAYMENT_METHOD_CREDIT = 'CREDIT';
    const PAYMENT_METHOD_CASH = 'CASH';
    const PAYMENT_METHOD_BANK = 'BANK';
    const PAYMENT_METHOD_PAYPAL = 'PAYPAL';
    const PAYMENT_METHOD_WU = 'WU';
    const ACTION_SYSTEM_ADJUST = 'SYSTEM_ADJUST';
    const ACTION_CANCELLATION_ORDER_FEE = 'CANCELLATION_ORDER_FEE';
    const ACTION_EXCHANGE_POINT = 'EXCHANGE_POINT';
    const ACTION_REDEEM_POINT = 'REDEEM_POINT';
    const ACTION_TRANSFER_POINT = 'TRANSFER_POINT';
    const ACTION_TRIP_PAYMENT = 'TRIP_PAYMENT';
    const ACTION_PASSENGER_SHARE_BONUS = 'PASSENGER_SHARE_BONUS';
    const ACTION_DRIVER_SHARE_BONUS = 'DRIVER_SHARE_BONUS';
    const TYPE_PLUS = 'PLUS';
    const TYPE_MINUS = 'MINUS';
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = -1;

    public function fields()
    {
        $fields = parent::fields(); // TODO: Change the autogenerated stub
        //$folder = 'app-transaction';
        //$image = FApi::getImageUrlForAPI($this->image, $folder);
        //$this->image = $image;
        return $fields;
    }

    public function getApiFields()
    {
        //$fields = parent::getApiFields(); // TODO: Change the autogenerated stub
        $fields = [
            'id',
            'transaction_id',
            'user_id',
            'receiver_user_id',
            'object_id',
            'object_type',
            'amount',
            'currency',
            'payment_method',
            'note',
            'time',
            'action',
            'type',
            'status'
        ];
        return $fields;
    }
}