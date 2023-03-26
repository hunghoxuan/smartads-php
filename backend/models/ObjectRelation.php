<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "object_relation".
 */
class ObjectRelation extends ObjectRelationBase //\yii\db\ActiveRecord
{
    public $order_by = 'sort_order';

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['ObjectRelation*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectRelation' => 'ObjectRelation.php',
            ],
        ];
    }

    private $object;
    public function getObject() {
         if (!isset($this->object)) {
             $this->object = FHtml::findOne($this->object_type, ['id' => $this->object_id]);
         }
         return $this->object;
    }

    private $object2;
    public function getObject2() {
        if (!isset($this->object2)) {
            $this->object2 = FHtml::findOne($this->object2_type, ['id' => $this->object2_id]);
        }
        return $this->object2;
    }
}
