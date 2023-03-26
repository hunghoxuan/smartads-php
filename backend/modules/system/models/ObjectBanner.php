<?php

namespace backend\modules\system\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;


class ObjectBanner extends ObjectBannerBase
{
    const LOOKUP = [
        'platform' => [
            ['id' => ObjectBanner::PLATFORM_ANDROID, 'name' => 'android'],
            ['id' => ObjectBanner::PLATFORM_IOS, 'name' => 'ios'],
            ['id' => ObjectBanner::PLATFORM_WEB, 'name' => 'web'],
            ['id' => ObjectBanner::PLATFORM_ALL, 'name' => 'all'],
        ],
        'position' => [
            ['id' => ObjectBanner::POSITION_DASHBOARD, 'name' => 'dashboard'],
            ['id' => ObjectBanner::POSITION_TOP, 'name' => 'top'],
            ['id' => ObjectBanner::POSITION_BOTTOM, 'name' => 'bottom'],
            ['id' => ObjectBanner::POSITION_LEFT, 'name' => 'left'],
            ['id' => ObjectBanner::POSITION_RIGHT, 'name' => 'right'],
            ['id' => ObjectBanner::POSITION_CENTER, 'name' => 'center'],
            ['id' => ObjectBanner::POSITION_MIDDLE, 'name' => 'middle'],
        ],
        'type' => [
            ['id' => ObjectBanner::TYPE_BANNER, 'name' => 'banner'],
            ['id' => ObjectBanner::TYPE_BLOCK, 'name' => 'block'],
            ['id' => ObjectBanner::TYPE_VERTICAL, 'name' => 'vertical'],
            ['id' => ObjectBanner::TYPE_HORIZONTAL, 'name' => 'horizontal'],
            ['id' => ObjectBanner::TYPE_FULL_SCREEN, 'name' => 'full-screen'],
        ],
    ];

    const COLUMNS_UPLOAD = ['image' ];

    public $order_by = 'id desc, sort_order asc';

    const OBJECTS_META = [];
    const OBJECTS_RELATED = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }




    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

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
        $query = ObjectBanner::find();

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
                'image' => $this->image,
                'title' => $this->title,
                'link_url' => $this->link_url,
                'platform' => $this->platform,
                'position' => $this->position,
                'type' => $this->type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'object_id' => $this->object_id,
                'object_type' => $this->object_type,
                'platform' => $this->platform,
                'position' => $this->position,
                'type' => $this->type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'link_url', $this->link_url]);
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