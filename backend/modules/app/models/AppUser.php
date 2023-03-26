<?php

namespace backend\modules\app\models;

use common\components\FConstant;
use common\components\FHtml;
use common\components\FActiveDataProvider;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


class AppUser extends AppUserBase
{
    const STATUS_PENDING = 1;
    const STATUS_BANNED =1;
    const STATUS_REJECTED =1;
    const STATUS_NORMAL =1;
    const STATUS_PRO =1;
    const STATUS_VIP =1;

    const LOOKUP = [
    'is_active' => [],
        'type' => [
            ['id' => AppUser::TYPE_USER, 'name' => 'USER'],
            ['id' => AppUser::TYPE_VIP, 'name' => 'VIP'],
            ['id' => AppUser::TYPE_PREMIUM, 'name' => 'PREMIUM'],
        ],
        'status' => [
            ['id' => AppUser::STATUS_PENDING, 'name' => 'PENDING'],
            ['id' => AppUser::STATUS_BANNED, 'name' => 'BANNED'],
            ['id' => AppUser::STATUS_REJECTED, 'name' => 'REJECTED'],
            ['id' => AppUser::STATUS_NORMAL, 'name' => 'NORMAL'],
        ],
        'role' => [
            ['id' => AppUser::ROLE_ADMIN, 'name' => 'ADMIN'],
        ],
    ];

    const COLUMNS_UPLOAD = ['avatar' ];

    public $order_by = 'created_date desc';

    const OBJECTS_META = [];
    const OBJECTS_RELATED = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }


    public function getAvatar() {
        return $this->image;
    }

    public function setAvatar($value) {
        $this->image = $value;
    }

    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

    }

    // Lookup Object: provider\n
    public $provider;
    public function getProvider() {
        if (!isset($this->provider))
        $this->provider = FHtml::getModel('provider', '', $this->provider_id, '', false);

        return $this->provider;
    }

    public function setProvider($value) {
        $this->provider = $value;
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

    public static function findByEmail($email)
    {
        return AppUser::findOne(['email' => $email]);
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

    //connections
    public function getDevice()
    {
        return $this->hasOne(AppUserDeviceAPI::className(), ['user_id' => 'id']);
    }

    public function getPro()
    {
        return $this->hasOne(AppUserProAPI::className(), ['user_id' => 'id']);
    }

    public function getLoginToken()
    {
        return $this->hasOne(AppUserTokenAPI::className(), ['user_id' => 'id']);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['AppUser*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AppUser' => 'AppUser.php',
            ],
        ];
    }

    public function beforeSave($insert)
    {
        $this->role = FHtml::ROLE_USER;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
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
        $query = AppUser::find();

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
                'avatar' => $this->avatar,
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'auth_id' => $this->auth_id,
                'auth_key' => $this->auth_key,
                'password_hash' => $this->password_hash,
                'password_reset_token' => $this->password_reset_token,
                'description' => $this->description,
                'content' => $this->content,
                'gender' => $this->gender,
                'dob' => $this->dob,
                'phone' => $this->phone,
                'weight' => $this->weight,
                'height' => $this->height,
                'address' => $this->address,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'balance' => $this->balance,
                'point' => $this->point,
                'lat' => $this->lat,
                'long' => $this->long,
                'rate' => $this->rate,
                'rate_count' => $this->rate_count,
                'is_online' => $this->is_online,
                'is_active' => $this->is_active,
                'type' => $this->type,
                'status' => $this->status,
                'role' => $this->role,
                'properties' => $this->properties,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'auth_id' => $this->auth_id,
                'auth_key' => $this->auth_key,
                'content' => $this->content,
                'gender' => $this->gender,
                'phone' => $this->phone,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'balance' => $this->balance,
                'point' => $this->point,
                'rate' => $this->rate,
                'rate_count' => $this->rate_count,
                'is_online' => $this->is_online,
                'is_active' => $this->is_active,
                'type' => $this->type,
                'status' => $this->status,
                'role' => $this->role,
                'properties' => $this->properties,
                'created_date' => $this->created_date,
                'modified_date' => $this->modified_date,
                'application_id' => $this->application_id,
            ]);

            $query->andFilterWhere(['like', 'avatar', $this->avatar])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'dob', $this->dob])
                ->andFilterWhere(['like', 'weight', $this->weight])
                ->andFilterWhere(['like', 'height', $this->height])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'lat', $this->lat])
                ->andFilterWhere(['like', 'long', $this->long]);
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