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
 * This is the model class for table "object_reviews".
 *

 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property double $rate
 * @property string $comment
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property integer $is_active
 * @property string $created_date
 * @property string $application_id
 */
class ObjectReviewsBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    // id, object_id, object_type, rate, comment, user_id, name, email, is_active, created_date, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_RATE = 'rate';
    const COLUMN_COMMENT = 'comment';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_NAME = 'name';
    const COLUMN_EMAIL = 'email';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_reviews';

    public static function tableName()
    {
        return 'object_reviews';
    }



    /**
     * @inheritdoc
     * @return ObjectReviewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectReviewsQuery(get_called_class());
    }
}
