<?php

namespace backend\modules\app\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;


class AppTransaction extends AppTransactionBase
{
    const LOOKUP = [
        'payment_method' => [
            ['id' => AppTransaction::PAYMENT_METHOD_POINT, 'name' => 'POINT'],
            ['id' => AppTransaction::PAYMENT_METHOD_CREDIT, 'name' => 'CREDIT'],
            ['id' => AppTransaction::PAYMENT_METHOD_CASH, 'name' => 'CASH'],
            ['id' => AppTransaction::PAYMENT_METHOD_BANK, 'name' => 'BANK'],
            ['id' => AppTransaction::PAYMENT_METHOD_PAYPAL, 'name' => 'PAYPAL'],
            ['id' => AppTransaction::PAYMENT_METHOD_WU, 'name' => 'WU'],
        ],
        'action' => [
            ['id' => AppTransaction::ACTION_SYSTEM_ADJUST, 'name' => 'SYSTEM_ADJUST'],
            ['id' => AppTransaction::ACTION_CANCELLATION_ORDER_FEE, 'name' => 'CANCELLATION_ORDER_FEE'],
            ['id' => AppTransaction::ACTION_EXCHANGE_POINT, 'name' => 'EXCHANGE_POINT'],
            ['id' => AppTransaction::ACTION_REDEEM_POINT, 'name' => 'REDEEM_POINT'],
            ['id' => AppTransaction::ACTION_TRANSFER_POINT, 'name' => 'TRANSFER_POINT'],
            ['id' => AppTransaction::ACTION_TRIP_PAYMENT, 'name' => 'TRIP_PAYMENT'],
            ['id' => AppTransaction::ACTION_PASSENGER_SHARE_BONUS, 'name' => 'PASSENGER_SHARE_BONUS'],
            ['id' => AppTransaction::ACTION_DRIVER_SHARE_BONUS, 'name' => 'DRIVER_SHARE_BONUS'],
        ],
        'type' => [
            ['id' => AppTransaction::TYPE_PLUS, 'name' => 'PLUS'],
            ['id' => AppTransaction::TYPE_MINUS, 'name' => 'MINUS'],
        ],
        'status' => [
            ['id' => AppTransaction::STATUS_PENDING, 'name' => 'PENDING'],
            ['id' => AppTransaction::STATUS_APPROVED, 'name' => 'APPROVED'],
            ['id' => AppTransaction::STATUS_REJECTED, 'name' => 'REJECTED'],
        ],
    ];

    const COLUMNS_UPLOAD = [];


    const OBJECTS_META = [];
    const OBJECTS_RELATED = [];

    public static function getLookupArray($column)
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }



    // Lookup Object: receiver_user
    public $receiver_user;

    public function getReceiverUser()
    {
        if (!isset($this->receiver_user))
            $this->receiver_user = FHtml::getModel('app_user', '', $this->receiver_user_id, '', false);

        return $this->receiver_user;
    }

    public function setReceiverUser($value)
    {
        $this->receiver_user = $value;
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

        $this->receiver_user = self::getReceiverUser();
    }

    public function getPreviewFields() {
        return ['name'];
    }

    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }

    public function getDefaultFindParams()
    {
        return [];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $andWhere = '')
    {
        $query = AppTransaction::find();

        $dataProvider = new FActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        //}

        if ($searchExact) {
            $query->andFilterWhere([
                'id' => $this->id,
                'transaction_id' => $this->transaction_id,
                'user_id' => $this->user_id,
                'receiver_user_id' => $this->receiver_user_id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'payment_method' => $this->payment_method,
                'note' => $this->note,
                'time' => $this->time,
                'action' => $this->action,
                'type' => $this->type,
                'status' => $this->status,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'user_id' => $this->user_id,
                'receiver_user_id' => $this->receiver_user_id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'payment_method' => $this->payment_method,
                'time' => $this->time,
                'type' => $this->type,
                'status' => $this->status,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'modified_date' => $this->modified_date,
                'modified_user' => $this->modified_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
                ->andFilterWhere(['like', 'note', $this->note])
                ->andFilterWhere(['like', 'action', $this->action]);
        }

        if (!empty($andWhere))
            $query->andWhere($andWhere);

        $params = $this->getDefaultFindParams();
        if (!empty($params))
            $query->andWhere($params);

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}