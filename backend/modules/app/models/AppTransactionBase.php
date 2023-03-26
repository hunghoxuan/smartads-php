<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


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
class AppTransactionBase extends \common\models\BaseModel //\yii\db\ActiveRecord
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

    /**
     * @inheritdoc
     */
    public $tableName = 'app_transaction';

    public static function tableName()
    {
        return 'app_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'transaction_id', 'user_id', 'receiver_user_id', 'object_id', 'object_type', 'amount', 'currency', 'payment_method', 'note', 'time', 'action', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['transaction_id', 'user_id', 'receiver_user_id', 'amount', 'payment_method', 'time', 'status'], 'required'],
            [['amount'], 'number'],
            [['created_date', 'modified_date'], 'safe'],
            [['transaction_id', 'action'], 'string', 'max' => 255],
            [['user_id', 'receiver_user_id', 'object_id', 'object_type', 'currency', 'payment_method', 'type', 'status', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 2000],
            [['time'], 'string', 'max' => 20],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppTransaction', 'ID'),
            'transaction_id' => FHtml::t('AppTransaction', 'Transaction ID'),
            'user_id' => FHtml::t('AppTransaction', 'User ID'),
            'receiver_user_id' => FHtml::t('AppTransaction', 'Receiver User ID'),
            'object_id' => FHtml::t('AppTransaction', 'Object ID'),
            'object_type' => FHtml::t('AppTransaction', 'Object Type'),
            'amount' => FHtml::t('AppTransaction', 'Amount'),
            'currency' => FHtml::t('AppTransaction', 'Currency'),
            'payment_method' => FHtml::t('AppTransaction', 'Payment Method'),
            'note' => FHtml::t('AppTransaction', 'Note'),
            'time' => FHtml::t('AppTransaction', 'Time'),
            'action' => FHtml::t('AppTransaction', 'Action'),
            'type' => FHtml::t('AppTransaction', 'Type'),
            'status' => FHtml::t('AppTransaction', 'Status'),
            'created_date' => FHtml::t('AppTransaction', 'Created Date'),
            'created_user' => FHtml::t('AppTransaction', 'Created User'),
            'modified_date' => FHtml::t('AppTransaction', 'Modified Date'),
            'modified_user' => FHtml::t('AppTransaction', 'Modified User'),
            'application_id' => FHtml::t('AppTransaction', 'Application ID'),
        ];
    }


}