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
 * This is the model class for table "object_favourites".
 *

 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property integer $user_id
 * @property string $created_date
 * @property string $application_id
 */
class ObjectFavouritesBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    // id, object_id, object_type, user_id, created_date, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_favourites';

    public static function tableName()
    {
        return 'object_favourites';
    }



    /**
     * @inheritdoc
     * @return ObjectFavouritesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectFavouritesQuery(get_called_class());
    }
}
