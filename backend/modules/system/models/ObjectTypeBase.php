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
 * This is the model class for table "object_type".
 *

 * @property string $object_type
 * @property string $group
 * @property string $name
 * @property integer $sort_order
 * @property integer $is_active
 * @property integer $is_system
 */
class ObjectTypeBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

// object_type, group, name, sort_order, is_active, is_system
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_GROUP = 'group';
    const COLUMN_NAME = 'name';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_IS_SYSTEM = 'is_system';

    /**
    * @inheritdoc
    */
    public $tableName = 'object_type';

    public static function tableName()
    {
        return 'object_type';
    }

    /**
     * @inheritdoc
     * @return ObjectTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectTypeQuery(get_called_class());
    }

    public static function getDb()
    {
        return FHtml::currentDb();
    }
}
