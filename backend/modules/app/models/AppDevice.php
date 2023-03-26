<?php

namespace backend\modules\app\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;

class AppDevice extends AppDeviceBase
{
    const LOOKUP = [
        'type' => [
            ['id' => AppDevice::TYPE_ANDROID, 'name' => 'android'],
            ['id' => AppDevice::TYPE_IOS, 'name' => 'ios'],
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
        $query = AppDevice::find();

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
                'user_id' => $this->user_id,
                'imei' => $this->imei,
                'token' => $this->token,
                'type' => $this->type,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'user_id' => $this->user_id,
                'type' => $this->type,
                'is_active' => $this->is_active,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'imei', $this->imei])
                ->andFilterWhere(['like', 'token', $this->token]);
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