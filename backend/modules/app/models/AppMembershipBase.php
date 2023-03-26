<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "app_membership".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $service
 * @property string $type
 * @property integer $expiry
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppMembershipBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const SERVICE_BUSINESS = 'business';
    const SERVICE_LIBRARY = 'library';
    const SERVICE_ECOMMERCE = 'ecommerce';
    const SERVICE_CONTENT = 'content';
    const TYPE_VIP = 'vip';
    const TYPE_PREMIUM = 'premium';
    const TYPE_PRO = 'pro';

    /**
     * @inheritdoc
     */
    public $tableName = 'app_membership';

    public static function tableName()
    {
        return 'app_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'service', 'type', 'expiry', 'is_active', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['user_id', 'type', 'expiry', 'is_active'], 'required'],
            [['user_id', 'expiry', 'is_active'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['service', 'application_id'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppMembership', 'ID'),
            'user_id' => FHtml::t('AppMembership', 'User ID'),
            'service' => FHtml::t('AppMembership', 'Service'),
            'type' => FHtml::t('AppMembership', 'Type'),
            'expiry' => FHtml::t('AppMembership', 'Expiry'),
            'is_active' => FHtml::t('AppMembership', 'Is Active'),
            'created_date' => FHtml::t('AppMembership', 'Created Date'),
            'modified_date' => FHtml::t('AppMembership', 'Modified Date'),
            'application_id' => FHtml::t('AppMembership', 'Application ID'),
        ];
    }


}