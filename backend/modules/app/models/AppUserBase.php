<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "app_user".
 *
 * @property integer $id
 * @property string $avatar
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $auth_id
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $description
 * @property string $content
 * @property string $gender
 * @property string $dob
 * @property string $phone
 * @property string $weight
 * @property string $height
 * @property string $address
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $balance
 * @property integer $point
 * @property string $lat
 * @property string $long
 * @property double $rate
 * @property integer $rate_count
 * @property integer $is_online
 * @property integer $is_active
 * @property string $type
 * @property string $status
 * @property integer $role
 * @property string $properties
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppUserBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_USER = 'USER';
    const TYPE_VIP = 'VIP';
    const TYPE_PREMIUM = 'PREMIUM';
    const STATUS_PENDING = 'PENDING';
    const STATUS_BANNED = 'BANNED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_NORMAL = 'NORMAL';
    const ROLE_ADMIN = 'ADMIN';

    /**
     * @inheritdoc
     */
    public $tableName = 'app_user';

    public static function tableName()
    {
        return 'app_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'avatar', 'name', 'username', 'email', 'password', 'auth_id', 'auth_key', 'password_hash', 'password_reset_token', 'description', 'content', 'gender', 'dob', 'phone', 'weight', 'height', 'address', 'country', 'state', 'city', 'balance', 'point', 'lat', 'long', 'rate', 'rate_count', 'is_online', 'is_active', 'type', 'status', 'role', 'properties', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name', 'username', 'email', 'password', 'is_active'], 'required'],
            [['auth_id', 'point', 'rate_count', 'is_online', 'is_active', 'role'], 'integer'],
            [['content', 'properties'], 'string'],
            [['balance', 'rate'], 'number'],
            [['created_date', 'modified_date'], 'safe'],
            [['avatar', 'name', 'username', 'email', 'password', 'password_hash', 'password_reset_token', 'dob', 'weight', 'height', 'address', 'lat', 'long'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 2000],
            [['gender', 'country', 'state', 'city', 'type', 'status', 'application_id'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 25],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppUser', 'ID'),
            'avatar' => FHtml::t('AppUser', 'Avatar'),
            'name' => FHtml::t('AppUser', 'Name'),
            'username' => FHtml::t('AppUser', 'Username'),
            'email' => FHtml::t('AppUser', 'Email'),
            'password' => FHtml::t('AppUser', 'Password'),
            'auth_id' => FHtml::t('AppUser', 'Auth ID'),
            'auth_key' => FHtml::t('AppUser', 'Auth Key'),
            'password_hash' => FHtml::t('AppUser', 'Password Hash'),
            'password_reset_token' => FHtml::t('AppUser', 'Password Reset Token'),
            'description' => FHtml::t('AppUser', 'Description'),
            'content' => FHtml::t('AppUser', 'Content'),
            'gender' => FHtml::t('AppUser', 'Gender'),
            'dob' => FHtml::t('AppUser', 'Dob'),
            'phone' => FHtml::t('AppUser', 'Phone'),
            'weight' => FHtml::t('AppUser', 'Weight'),
            'height' => FHtml::t('AppUser', 'Height'),
            'address' => FHtml::t('AppUser', 'Address'),
            'country' => FHtml::t('AppUser', 'Country'),
            'state' => FHtml::t('AppUser', 'State'),
            'city' => FHtml::t('AppUser', 'City'),
            'balance' => FHtml::t('AppUser', 'Balance'),
            'point' => FHtml::t('AppUser', 'Point'),
            'lat' => FHtml::t('AppUser', 'Lat'),
            'long' => FHtml::t('AppUser', 'Long'),
            'rate' => FHtml::t('AppUser', 'Rate'),
            'rate_count' => FHtml::t('AppUser', 'Rate Count'),
            'is_online' => FHtml::t('AppUser', 'Is Online'),
            'is_active' => FHtml::t('AppUser', 'Is Active'),
            'type' => FHtml::t('AppUser', 'Type'),
            'status' => FHtml::t('AppUser', 'Status'),
            'role' => FHtml::t('AppUser', 'Role'),
            'properties' => FHtml::t('AppUser', 'Properties'),
            'created_date' => FHtml::t('AppUser', 'Created Date'),
            'modified_date' => FHtml::t('AppUser', 'Modified Date'),
            'application_id' => FHtml::t('AppUser', 'Application ID'),
        ];
    }


}