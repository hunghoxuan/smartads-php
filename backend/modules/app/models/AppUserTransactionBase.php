<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "app_user_transaction".
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
class AppUserTransactionBase extends BaseModel //\yii\db\ActiveRecord
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

// id, transaction_id, user_id, receiver_user_id, object_id, object_type, amount, currency, payment_method, note, time, action, type, status, created_date, created_user, modified_date, modified_user, application_id
    const COLUMN_ID = 'id';
    const COLUMN_TRANSACTION_ID = 'transaction_id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_RECEIVER_USER_ID = 'receiver_user_id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_CURRENCY = 'currency';
    const COLUMN_PAYMENT_METHOD = 'payment_method';
    const COLUMN_NOTE = 'note';
    const COLUMN_TIME = 'time';
    const COLUMN_ACTION = 'action';
    const COLUMN_TYPE = 'type';
    const COLUMN_STATUS = 'status';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_MODIFIED_DATE = 'modified_date';
    const COLUMN_MODIFIED_USER = 'modified_user';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
    * @inheritdoc
    */
    public $tableName = 'app_user_transaction';

    public static function tableName()
    {
        return 'app_user_transaction';
    }



    /**
     * @inheritdoc
     * @return \backend\models\AppUserTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\AppUserTransactionQuery(get_called_class());
    }
}
