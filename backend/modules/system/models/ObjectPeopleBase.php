<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;

/**
 *
 ***
 * This is the model class for table "object_people".
 *

 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $name
 * @property string $title
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $facebook
 * @property string $google
 * @property string $linkedin
 * @property string $skype
 * @property string $instagram
 * @property string $twitter
 * @property string $yahoo
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $application_id
 */
class ObjectPeopleBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    // id, object_id, object_type, name, title, phone, email, address, facebook, google, linkedin, skype, instagram, twitter, yahoo, sort_order, is_active, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_NAME = 'name';
    const COLUMN_TITLE = 'title';
    const COLUMN_PHONE = 'phone';
    const COLUMN_EMAIL = 'email';
    const COLUMN_ADDRESS = 'address';
    const COLUMN_FACEBOOK = 'facebook';
    const COLUMN_GOOGLE = 'google';
    const COLUMN_LINKEDIN = 'linkedin';
    const COLUMN_SKYPE = 'skype';
    const COLUMN_INSTAGRAM = 'instagram';
    const COLUMN_TWITTER = 'twitter';
    const COLUMN_YAHOO = 'yahoo';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_people';

    public static function tableName()
    {
        return 'object_people';
    }



    /**
     * @inheritdoc
     * @return ObjectPeopleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectPeopleQuery(get_called_class());
    }
}
