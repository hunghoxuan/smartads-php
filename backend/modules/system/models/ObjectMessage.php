<?php

namespace backend\modules\system\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;


class ObjectMessage extends ObjectMessageBase
{
    const LOOKUP = [
        'method' => [
            ['id' => ObjectMessage::METHOD_PUSH, 'name' => 'push'],
            ['id' => ObjectMessage::METHOD_EMAIL, 'name' => 'email'],
            ['id' => ObjectMessage::METHOD_SMS, 'name' => 'sms'],
        ],
        'type' => [
            ['id' => ObjectMessage::TYPE_NOTIFY, 'name' => 'notify'],
            ['id' => ObjectMessage::TYPE_WARNING, 'name' => 'warning'],
            ['id' => ObjectMessage::TYPE_BIRTHDAY, 'name' => 'birthday'],
            ['id' => ObjectMessage::TYPE_PROMOTION, 'name' => 'promotion'],
            ['id' => ObjectMessage::TYPE_REMIND, 'name' => 'remind'],
        ],
        'status' => [
            ['id' => ObjectMessage::STATUS_PENDING, 'name' => 'pending'],
            ['id' => ObjectMessage::STATUS_SENT, 'name' => 'sent'],
        ],
    ];

    const COLUMNS_UPLOAD = [];


    const OBJECTS_META = [];
    const OBJECTS_RELATED = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }



    // Lookup Object: object
    public $object;

    public function getObject()
    {
        if (!isset($this->object))
            $this->object = FHtml::getModel('app_user', '', $this->object_id, '', false);

        return $this->object;
    }

    public function setObject($value)
    {
        $this->object = $value;
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

        $this->object = self::getObject();
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
        $query = ObjectMessage::find();

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
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'title' => $this->title,
                'message' => $this->message,
                'method' => $this->method,
                'send_date' => $this->send_date,
                'sender_id' => $this->sender_id,
                'sender_type' => $this->sender_type,
                'type' => $this->type,
                'status' => $this->status,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'method' => $this->method,
                'send_date' => $this->send_date,
                'sender_id' => $this->sender_id,
                'sender_type' => $this->sender_type,
                'type' => $this->type,
                'status' => $this->status,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'created_user' => $this->created_user,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'message', $this->message]);
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