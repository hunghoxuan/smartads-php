<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "auth_role".
 */
class AuthRole extends AuthRoleBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,created_date desc,';

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

            [['id', 'code', 'name', 'description', 'is_active', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],

            [['code', 'name'], 'required'],
            [['is_active'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['code'], 'string', 'max' => 20],
            [['name', 'description'], 'string', 'max' => 255],
            [['application_id'], 'string', 'max' => 100],
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
