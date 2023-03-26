<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_request".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $name
 * @property string $email
 * @property string $type
 * @property integer $is_active
 * @property integer $user_id
 * @property string $user_type
 * @property string $user_role
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class ObjectRequestBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_VIP = 'vip';
    const TYPE_MODERATOR = 'moderator';
    const TYPE_UNLOCK = 'unlock';
    const USER_TYPE_APP_USER = 'app_user';
    const USER_TYPE_USER = 'user';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_request';

    public static function tableName()
    {
        return 'object_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'name', 'email', 'type', 'is_active', 'user_id', 'user_type', 'user_role', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id', 'is_active', 'user_id'], 'required'],
            [['object_id', 'is_active', 'user_id'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['object_type', 'type', 'user_type', 'user_role', 'application_id'], 'string', 'max' => 100],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectRequest', 'ID'),
            'object_id' => FHtml::t('ObjectRequest', 'Object ID'),
            'object_type' => FHtml::t('ObjectRequest', 'Object Type'),
            'name' => FHtml::t('ObjectRequest', 'Name'),
            'email' => FHtml::t('ObjectRequest', 'Email'),
            'type' => FHtml::t('ObjectRequest', 'Type'),
            'is_active' => FHtml::t('ObjectRequest', 'Is Active'),
            'user_id' => FHtml::t('ObjectRequest', 'User ID'),
            'user_type' => FHtml::t('ObjectRequest', 'User Type'),
            'user_role' => FHtml::t('ObjectRequest', 'User Role'),
            'created_date' => FHtml::t('ObjectRequest', 'Created Date'),
            'modified_date' => FHtml::t('ObjectRequest', 'Modified Date'),
            'application_id' => FHtml::t('ObjectRequest', 'Application ID'),
        ];
    }


}