<?php

namespace backend\modules\system\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;


class ObjectActivity extends ObjectActivityBase
{
    const LOOKUP = [
        'type' => [
            ['id' => ObjectActivity::TYPE_LIKE, 'name' => 'like'],
            ['id' => ObjectActivity::TYPE_SHARE, 'name' => 'share'],
            ['id' => ObjectActivity::TYPE_FAVOURITE, 'name' => 'favourite'],
            ['id' => ObjectActivity::TYPE_RATE, 'name' => 'rate'],
        ],
        'user_type' => [
            ['id' => ObjectActivity::USER_TYPE_APP_USER, 'name' => 'app_user'],
            ['id' => ObjectActivity::USER_TYPE_USER, 'name' => 'user'],
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



    // Lookup Object: user
    public $user;

    public function getUser()
    {
        if (!isset($this->user))
            $this->user = FHtml::getModel('app_user', '', $this->user_id, '', false);

        return $this->user;
    }

    public function setUser($value)
    {
        $this->user = $value;
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

        $this->user = self::getUser();
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
        $query = ObjectActivity::find();

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
                'type' => $this->type,
                'user_id' => $this->user_id,
                'user_type' => $this->user_type,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'type' => $this->type,
                'user_id' => $this->user_id,
                'user_type' => $this->user_type,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);
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