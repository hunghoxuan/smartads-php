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
 * This is the customized model class for table "object_actions".
 */
class ObjectActions extends ObjectActionsBase //\yii\db\ActiveRecord
{
    const LOOKUP = ['action' => [
        ['id' => ObjectActions::ACTION_CREATE, 'name' => 'create'],
        ['id' => ObjectActions::ACTION_UPDATE, 'name' => 'update'],
        ['id' => ObjectActions::ACTION_DELETE, 'name' => 'delete'],
    ],
    ];

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

            [['id', 'object_id', 'object_type', 'name', 'old_content', 'content', 'action', 'is_active', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id', 'object_type', 'content', 'action'], 'required'],
            [['old_content', 'content'], 'string'],
            [['is_active'], 'integer'],
            [['created_date'], 'safe'],
            [['object_id', 'object_type', 'action', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 2000],
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

    public function getShowName()
    {
        return $this->name;
    }

    public function getShowContent()
    {
        return FHtml::showObjectActions($this, ['code', 'old_value', 'new_value']);
    }

    public function getShowOldContent()
    {
        return FHtml::showJsonAsTable($this->old_content);
    }
}
