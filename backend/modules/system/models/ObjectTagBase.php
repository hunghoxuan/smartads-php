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
 * This is the model class for table "object_tag".
 *

 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $tag
 * @property integer $sort_order
 * @property string $application_id
 */
class ObjectTagBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

// id, object_id, object_type, tag, sort_order, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_TAG = 'tag';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
    * @inheritdoc
    */
    public $tableName = 'object_tag';

    public static function tableName()
    {
        return 'object_tag';
    }



    /**
     * @inheritdoc
     * @return ObjectTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectTagQuery(get_called_class());
    }
}
