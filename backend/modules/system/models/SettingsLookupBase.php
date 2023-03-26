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
 * This is the model class for table "settings_lookup".
 *

 * @property integer $id
 * @property string $name
 * @property string $object_type
 * @property string $params
 * @property string $fields
 * @property string $orderby
 * @property string $limit
 * @property string $sql
 * @property integer $is_cached
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_user
 * @property string $created_date
 * @property string $application_id
 */
class SettingsLookupBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

// id, name, object_type, params, fields, orderby, limit, sql, is_cached, is_active, sort_order, created_user, created_date, application_id
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_PARAMS = 'params';
    const COLUMN_FIELDS = 'fields';
    const COLUMN_ORDERBY = 'orderby';
    const COLUMN_LIMIT = 'limit';
    const COLUMN_SQL = 'sql';
    const COLUMN_IS_CACHED = 'is_cached';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
    * @inheritdoc
    */
    public $tableName = 'settings_lookup';

    public static function tableName()
    {
        return 'settings_lookup';
    }



    /**
     * @inheritdoc
     * @return SettingsLookupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsLookupQuery(get_called_class());
    }
}
