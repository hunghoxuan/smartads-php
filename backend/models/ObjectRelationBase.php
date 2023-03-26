<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "object_relation".
 *
 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $object2_id
 * @property string $object2_type
 * @property string $relation_type
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 */
class ObjectRelationBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    // Constants

    // id, object_id, object_type, object2_id, object2_type, relation_type, sort_order, created_date, created_user
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_OBJECT2_ID = 'object2_id';
    const COLUMN_OBJECT2_TYPE = 'object2_type';
    const COLUMN_RELATION_TYPE = 'relation_type';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';

    const COLUMNS_UPLOAD = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_relation';
    }

    public function getTableName()
    {
        return 'object_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'object2_id', 'object2_type', 'relation_type', 'sort_order', 'created_date', 'created_user'], 'filter', 'filter' => 'trim'],

            [['object_id', 'object_type', 'object2_id', 'object2_type', 'sort_order'], 'required'],
            [['object_id', 'object2_id', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'object2_type', 'relation_type', 'created_user'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectRelation', 'ID'),
            'object_id' => FHtml::t('ObjectRelation', 'Object ID'),
            'object_type' => FHtml::t('ObjectRelation', 'Object Type'),
            'object2_id' => FHtml::t('ObjectRelation', 'Object2 ID'),
            'object2_type' => FHtml::t('ObjectRelation', 'Object2 Type'),
            'relation_type' => FHtml::t('ObjectRelation', 'Relation Type'),
            'sort_order' => FHtml::t('ObjectRelation', 'Sort Order'),
            'created_date' => FHtml::t('ObjectRelation', 'Created Date'),
            'created_user' => FHtml::t('ObjectRelation', 'Created User'),
        ];
    }

    /**
     * @inheritdoc
     * @return ObjectRelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectRelationQuery(get_called_class());
    }
}
