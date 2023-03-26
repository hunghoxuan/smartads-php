<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "app_user_bookmark".
 *

 * @property integer $id
 * @property string $user_id
 * @property string $object_id
 * @property string $object_type
 * @property string $name
 * @property string $content
 * @property string $created_date
 * @property string $application_id
 */
class AppUserBookmarkBase extends BaseModel //\yii\db\ActiveRecord
{

// id, user_id, object_id, object_type, name, content, created_date, application_id
    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_NAME = 'name';
    const COLUMN_CONTENT = 'content';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
    * @inheritdoc
    */
    public $tableName = 'app_user_bookmark';

    public static function tableName()
    {
        return 'app_user_bookmark';
    }



    /**
     * @inheritdoc
     * @return AppUserBookmarkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserBookmarkQuery(get_called_class());
    }
}
