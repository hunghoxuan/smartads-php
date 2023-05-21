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
 * This is the model class for table "object_booking".
 *

 * @property integer $id
 * @property string $object_id
 * @property string $object_type
 * @property string $user_id
 * @property string $start_date
 * @property string $end_date
 * @property integer $is_active
 * @property string $type
 * @property string $status
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class ObjectBookingBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const STATUS_LOST = 'Lost';

    // id, object_id, object_type, user_id, start_date, end_date, is_active, type, status, created_date, created_user, modified_date, modified_user, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_START_DATE = 'start_date';
    const COLUMN_END_DATE = 'end_date';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_TYPE = 'type';
    const COLUMN_STATUS = 'status';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_MODIFIED_DATE = 'modified_date';
    const COLUMN_MODIFIED_USER = 'modified_user';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_booking';

    public static function tableName()
    {
        return 'object_booking';
    }



    /**
     * @inheritdoc
     * @return ObjectBookingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectBookingQuery(get_called_class());
    }
}
