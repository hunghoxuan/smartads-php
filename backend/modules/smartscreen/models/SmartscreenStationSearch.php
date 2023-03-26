<?php

namespace backend\modules\smartscreen\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenStation;

/**
 * SmartscreenStation represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenStation`.
 */
class SmartscreenStationSearch extends SmartscreenStationBase {
    const FIELD_CAMPAIGN_ID = 'branch_id';

    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];

        return $arr;
    }

    public $order_by = 'channel_id asc, dept_id desc, room_id asc, name asc, last_update desc';

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $andWhere = '')
    {
        $query = SmartscreenStation::find();

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
                'name' => $this->name,
                'description' => $this->description,
                'ime' => $this->ime,
                'status' => $this->status,
                'last_activity' => $this->last_activity,
                'last_update' => $this->last_update,
                'ScreenName' => $this->ScreenName,
                'MACAddress' => $this->MACAddress,
                'LicenseKey' => $this->LicenseKey,
                'branch_id' => $this->branch_id,
                'channel_id' => $this->channel_id,
                'dept_id' => $this->dept_id,
                'room_id' => $this->room_id,
                'disk_storage' => $this->disk_storage,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'ime' => $this->ime,
                'status' => $this->status,
                'last_update' => $this->last_update,
                'branch_id' => $this->branch_id,
                'channel_id' => $this->channel_id,
                'dept_id' => $this->dept_id,
                'room_id' => $this->room_id,
                'disk_storage' => $this->disk_storage,
                'created_date' => $this->created_date,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'last_activity', $this->last_activity])
                ->andFilterWhere(['like', 'ScreenName', $this->ScreenName])
                ->andFilterWhere(['like', 'MACAddress', $this->MACAddress])
                ->andFilterWhere(['like', 'LicenseKey', $this->LicenseKey]);
        }

        if (!empty($andWhere))
            $query->andWhere($andWhere);

        $params = $this->getDefaultFindParams();
        if (!empty($params))
            $query->andWhere($params);

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby($this->getOrderBy());

        return $dataProvider;
    }
}