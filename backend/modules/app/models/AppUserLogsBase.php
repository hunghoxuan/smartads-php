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
 * This is the model class for table "app_user_logs".
 *

 * @property string $id
 * @property string $user_id
 * @property string $action
 * @property integer $duration
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppUserLogsBase extends BaseModel //\yii\db\ActiveRecord
{
    const ACTION_REGISTER = 'register';
    const ACTION_LOGIN = 'login';
    const ACTION_PURCHASE = 'purchase';
    const ACTION_FEEDBACK = 'feedback';

    // id, user_id, action, duration, created_date, modified_date, application_id
    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_ACTION = 'action';
    const COLUMN_DURATION = 'duration';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_MODIFIED_DATE = 'modified_date';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'app_user_logs';

    public static function tableName()
    {
        return 'app_user_logs';
    }



    /**
     * @inheritdoc
     * @return AppUserLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserLogsQuery(get_called_class());
    }
}
