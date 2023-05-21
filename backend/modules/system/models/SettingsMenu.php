<?php

namespace backend\modules\system\models;

use common\components\FSecurity;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "settings_menu".
 */
class SettingsMenu extends SettingsMenuBase //\yii\db\ActiveRecord
{
    public $role_array;

    const LOOKUP = [
        'group' => [['id' => SettingsMenu::GROUP_FRONTEND, 'name' => FRONTEND], ['id' => SettingsMenu::GROUP_BACKEND, 'name' => BACKEND],],
        'menu_type' => [['id' => SettingsMenu::MENU_TYPE_CATEGORY, 'name' => 'CATEGORY'], ['id' => SettingsMenu::MENU_TYPE_TYPE, 'name' => 'TYPE'], ['id' => SettingsMenu::MENU_TYPE_STATUS, 'name' => 'STATUS'], ['id' => SettingsMenu::MENU_TYPE_MIXED, 'name' => 'MIXED'],],
        'display_type' => [['id' => SettingsMenu::DISPLAY_TYPE_DEFAULT, 'name' => 'DEFAULT'], ['id' => SettingsMenu::DISPLAY_TYPE_TREE, 'name' => 'TREE'], ['id' => SettingsMenu::DISPLAY_TYPE_MEGA, 'name' => 'MEGA'],],
    ];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'module asc, id asc, sort_order asc,is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'icon', 'name', 'url', 'object_type', 'module', 'group', 'role', 'menu_type', 'display_type', 'sort_order', 'is_active', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['name'], 'required'],
            [['sort_order', 'is_active'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['icon'], 'string', 'max' => 300],
            [['name', 'url'], 'string', 'max' => 255],
            [['object_type', 'module', 'group', 'role', 'menu_type', 'display_type', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SettingsMenu', 'ID'),
            'icon' => FHtml::t('SettingsMenu', 'Icon'),
            'name' => FHtml::t('SettingsMenu', 'Name'),
            'url' => FHtml::t('SettingsMenu', 'Url'),
            'object_type' => FHtml::t('SettingsMenu', 'Object Type'),
            'module' => FHtml::t('SettingsMenu', 'Module'),
            'group' => FHtml::t('SettingsMenu', 'Group'),
            'role' => FHtml::t('SettingsMenu', 'Role'),
            'menu_type' => FHtml::t('SettingsMenu', 'Menu Type'),
            'display_type' => FHtml::t('SettingsMenu', 'Display Type'),
            'sort_order' => FHtml::t('SettingsMenu', 'Sort Order'),
            'is_active' => FHtml::t('SettingsMenu', 'Is Active'),
            'created_date' => FHtml::t('SettingsMenu', 'Created Date'),
            'created_user' => FHtml::t('SettingsMenu', 'Created User'),
            'modified_date' => FHtml::t('SettingsMenu', 'Modified Date'),
            'modified_user' => FHtml::t('SettingsMenu', 'Modified User'),
            'application_id' => FHtml::t('SettingsMenu', 'Application ID'),
        ];
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();
    }

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }

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
        $i18n->translations['SettingsMenu*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'SettingsMenu' => 'SettingsMenu.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['icon'], $this->icon);
        FHtml::setFieldValue($model, ['name'], $this->name);
        FHtml::setFieldValue($model, ['url'], $this->url);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['module'], $this->module);
        FHtml::setFieldValue($model, ['group'], $this->group);
        FHtml::setFieldValue($model, ['role'], $this->role);
        FHtml::setFieldValue($model, ['menu_type'], $this->menu_type);
        FHtml::setFieldValue($model, ['display_type'], $this->display_type);
        FHtml::setFieldValue($model, ['sort_order'], $this->sort_order);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['created_date'], $this->created_date);
        FHtml::setFieldValue($model, ['created_user'], $this->created_user);
        FHtml::setFieldValue($model, ['modified_date'], $this->modified_date);
        FHtml::setFieldValue($model, ['modified_user'], $this->modified_user);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }

    public function beforeSave($insert)
    {
        $this->role = FHtml::encode($this->role_array);

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->module)) {
            FSecurity::createAuthRole($this->module);
            FSecurity::createAuthRole($this->module, 'manage');
        }

        if (!empty($this->object_type)) {
            FSecurity::createAuthGroup($this->module, 'User', ['view']);

            FSecurity::createAuthGroup($this->module, 'Admin', ['manage']);
            FSecurity::createAuthRole($this->object_type);
            FSecurity::createAuthRole($this->object_type, 'manage');
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
