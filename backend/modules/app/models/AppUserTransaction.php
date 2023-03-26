<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "app_user_transaction".
 */
class AppUserTransaction extends AppUserTransactionBase //\yii\db\ActiveRecord
{
    const LOOKUP = [        'payment_method' => [['id' => AppUserTransaction::PAYMENT_METHOD_POINT, 'name' => 'POINT'], ['id' => AppUserTransaction::PAYMENT_METHOD_CREDIT, 'name' => 'CREDIT'], ['id' => AppUserTransaction::PAYMENT_METHOD_CASH, 'name' => 'CASH'], ['id' => AppUserTransaction::PAYMENT_METHOD_BANK, 'name' => 'BANK'], ['id' => AppUserTransaction::PAYMENT_METHOD_PAYPAL, 'name' => 'PAYPAL'], ['id' => AppUserTransaction::PAYMENT_METHOD_WU, 'name' => 'WU'], ],
        'action' => [['id' => AppUserTransaction::ACTION_SYSTEM_ADJUST, 'name' => 'SYSTEM_ADJUST'], ['id' => AppUserTransaction::ACTION_CANCELLATION_ORDER_FEE, 'name' => 'CANCELLATION_ORDER_FEE'], ['id' => AppUserTransaction::ACTION_EXCHANGE_POINT, 'name' => 'EXCHANGE_POINT'], ['id' => AppUserTransaction::ACTION_REDEEM_POINT, 'name' => 'REDEEM_POINT'], ['id' => AppUserTransaction::ACTION_TRANSFER_POINT, 'name' => 'TRANSFER_POINT'], ['id' => AppUserTransaction::ACTION_TRIP_PAYMENT, 'name' => 'TRIP_PAYMENT'], ['id' => AppUserTransaction::ACTION_PASSENGER_SHARE_BONUS, 'name' => 'PASSENGER_SHARE_BONUS'], ['id' => AppUserTransaction::ACTION_DRIVER_SHARE_BONUS, 'name' => 'DRIVER_SHARE_BONUS'], ],
        'type' => [['id' => AppUserTransaction::TYPE_PLUS, 'name' => 'PLUS'], ['id' => AppUserTransaction::TYPE_MINUS, 'name' => 'MINUS'], ],
        'status' => [['id' => AppUserTransaction::STATUS_PENDING, 'name' => 'PENDING'], ['id' => AppUserTransaction::STATUS_APPROVED, 'name' => 'APPROVED'], ['id' => AppUserTransaction::STATUS_REJECTED, 'name' => 'REJECTED'], ],
];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'transaction_id', 'user_id', 'receiver_user_id', 'object_id', 'object_type', 'amount', 'currency', 'payment_method', 'note', 'time', 'action', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id', ],
        'all' => ['id', 'transaction_id', 'user_id', 'receiver_user_id', 'object_id', 'object_type', 'amount', 'currency', 'payment_method', 'note', 'time', 'action', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['receiver_user',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

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
                    'id' => FHtml::t('AppUserTransaction', 'ID'),
                    'transaction_id' => FHtml::t('AppUserTransaction', 'Transaction ID'),
                    'user_id' => FHtml::t('AppUserTransaction', 'User ID'),
                    'receiver_user_id' => FHtml::t('AppUserTransaction', 'Receiver User ID'),
                    'object_id' => FHtml::t('AppUserTransaction', 'Object ID'),
                    'object_type' => FHtml::t('AppUserTransaction', 'Object Type'),
                    'amount' => FHtml::t('AppUserTransaction', 'Amount'),
                    'currency' => FHtml::t('AppUserTransaction', 'Currency'),
                    'payment_method' => FHtml::t('AppUserTransaction', 'Payment Method'),
                    'note' => FHtml::t('AppUserTransaction', 'Note'),
                    'time' => FHtml::t('AppUserTransaction', 'Time'),
                    'action' => FHtml::t('AppUserTransaction', 'Action'),
                    'type' => FHtml::t('AppUserTransaction', 'Type'),
                    'status' => FHtml::t('AppUserTransaction', 'Status'),
                    'created_date' => FHtml::t('AppUserTransaction', 'Created Date'),
                    'created_user' => FHtml::t('AppUserTransaction', 'Created User'),
                    'modified_date' => FHtml::t('AppUserTransaction', 'Modified Date'),
                    'modified_user' => FHtml::t('AppUserTransaction', 'Modified User'),
                    'application_id' => FHtml::t('AppUserTransaction', 'Application ID'),
                ];
    }



    // Lookup Object: receiver_user\n
    public $receiver_user;
    public function getReceiverUser() {
        if (!isset($this->receiver_user))
        $this->receiver_user = FHtml::getModel('user', '', $this->receiver_user_id, '', false);

        return $this->receiver_user;
    }
    public function setReceiverUser($value) {
        $this->receiver_user = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->receiver_user = self::getReceiverUser();
    }

    public function fields()
    {
        $fields = parent::fields();

        $columns = self::COLUMNS;
        if (is_string($this->columnsMode) && !empty($this->columnsMode) && key_exists($this->columnsMode, $columns)) {
            $fields1 = $columns[$this->columnsMode];
            if (!empty($fields1))
            $fields = $fields1;
        } else if (is_array($this->columnsMode))
            return $this->columnsMode;

        if (key_exists('+', $columns) && !empty($columns['+'])) {
            $fields = array_merge($fields, $columns['+']);
        }
        //unset($fields['xxx'], $fields['yyy'], $fields['zzz']);

        return $fields;
    }

    public static function getLookupArray($column = '') {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }


    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['AppUserTransaction*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AppUserTransaction' => 'AppUserTransaction.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['transaction_id'], $this->transaction_id);
            FHtml::setFieldValue($model, ['user_id'], $this->user_id);
            FHtml::setFieldValue($model, ['receiver_user_id'], $this->receiver_user_id);
            FHtml::setFieldValue($model, ['object_id'], $this->object_id);
            FHtml::setFieldValue($model, ['object_type'], $this->object_type);
            FHtml::setFieldValue($model, ['amount'], $this->amount);
            FHtml::setFieldValue($model, ['currency'], $this->currency);
            FHtml::setFieldValue($model, ['payment_method'], $this->payment_method);
            FHtml::setFieldValue($model, ['note'], $this->note);
            FHtml::setFieldValue($model, ['time'], $this->time);
            FHtml::setFieldValue($model, ['action'], $this->action);
            FHtml::setFieldValue($model, ['type'], $this->type);
            FHtml::setFieldValue($model, ['status'], $this->status);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['created_user'], $this->created_user);
            FHtml::setFieldValue($model, ['modified_date'], $this->modified_date);
            FHtml::setFieldValue($model, ['modified_user'], $this->modified_user);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
