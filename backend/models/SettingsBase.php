<?php

namespace backend\models;

use common\models\BaseModel;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;

/**
 *
 ***
 * This is the model class for table "settings".
 *

 * @property integer $id
 * @property string $metaKey
 * @property string $metaValue
 * @property string $group
 * @property string $editor
 * @property string $lookup
 * @property integer $is_system
 * @property string $application_id
 */
class SettingsBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    // id, metaKey, metaValue, group, editor, lookup, is_system, application_id
    const COLUMN_ID = 'id';
    const COLUMN_METAKEY = 'metaKey';
    const COLUMN_METAVALUE = 'metaValue';
    const COLUMN_GROUP = 'group';
    const COLUMN_EDITOR = 'editor';
    const COLUMN_LOOKUP = 'lookup';
    const COLUMN_IS_SYSTEM = 'is_system';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'settings';

    public static function tableName()
    {
        return 'settings';
    }



    /**
     * @inheritdoc
     * @return SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }
}
