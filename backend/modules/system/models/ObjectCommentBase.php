<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;

/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "object_comment".
 *

 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $parent_id
 * @property string $comment
 * @property string $app_user_id
 * @property string $user_id
 * @property string $user_type
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectCommentBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const USER_TYPE_APP_USER = 'app_user';
    const USER_TYPE_USER = 'user';

// id, object_id, object_type, parent_id, comment, app_user_id, user_id, user_type, created_date, created_user, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_PARENT_ID = 'parent_id';
    const COLUMN_COMMENT = 'comment';
    const COLUMN_APP_USER_ID = 'app_user_id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_USER_TYPE = 'user_type';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
    * @inheritdoc
    */
    public $tableName = 'object_comment';

    public static function tableName()
    {
        return 'object_comment';
    }

    /**
     * @inheritdoc
     * @return ObjectCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectCommentQuery(get_called_class());
    }
}
