<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
 *
 ***
 * This is the model class for table "app_user_pro".
 *

 * @property integer $user_id
 * @property double $rate
 * @property integer $rate_count
 * @property string $description
 * @property string $business_name
 * @property string $business_email
 * @property string $business_address
 * @property string $business_website
 * @property string $business_phone
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 */
class AppUserProBase extends BaseModel //\yii\db\ActiveRecord
{

    // user_id, rate, rate_count, description, business_name, business_email, business_address, business_website, business_phone, is_active, created_date, modified_date
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_RATE = 'rate';
    const COLUMN_RATE_COUNT = 'rate_count';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_BUSINESS_NAME = 'business_name';
    const COLUMN_BUSINESS_EMAIL = 'business_email';
    const COLUMN_BUSINESS_ADDRESS = 'business_address';
    const COLUMN_BUSINESS_WEBSITE = 'business_website';
    const COLUMN_BUSINESS_PHONE = 'business_phone';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_MODIFIED_DATE = 'modified_date';

    /**
     * @inheritdoc
     */
    public $tableName = 'app_user_pro';

    public static function tableName()
    {
        return 'app_user_pro';
    }



    /**
     * @inheritdoc
     * @return AppUserProQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserProQuery(get_called_class());
    }
}
