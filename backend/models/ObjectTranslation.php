<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_translation".
 */
class ObjectTranslation extends ObjectTranslationBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'lang', 'content', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id'], 'integer'],
            [['content'], 'string'],
            [['created_date'], 'safe'],
            [['object_type', 'lang', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }




    public function prepareCustomFields()
    {
        parent::prepareCustomFields();
    }


    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }
}
