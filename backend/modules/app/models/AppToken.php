<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "app_token".
 */
class AppToken extends AppTokenBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column = '') {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public function fields()
    {
        $fields = array_merge(parent::fields(), self::OBJECTS_RELATED);

        foreach (self::COLUMNS_UPLOAD as $field) {
            $this->{$field} = FHtml::getFileURL($this->{$field}, $this->getTableName());
        }
        return $fields;
    }




    public function prepareCustomFields() {
        parent::prepareCustomFields();

    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
