<?php

namespace backend\modules\system\models;

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
 * This is the model class for table "settings_text".
 *

 * @property string $id
 * @property string $group
 * @property string $name
 * @property string $lang
 * @property string $content
 * @property integer $is_active
 * @property string $application_id
 */
class SettingsTextBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'settings_text';

    public static function tableName()
    {
        return 'settings_text';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['id', 'name', 'lang',  'application_id'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [[ 'name', 'lang'], 'string', 'max' => 255],
            [['application_id'], 'string', 'max' => 100],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('SettingsText', 'ID'),
                    'name' => FHtml::t('SettingsText', 'Name'),
                    'lang' => FHtml::t('SettingsText', 'Lang'),
                    'content' => FHtml::t('SettingsText', 'Content'),
                    'application_id' => FHtml::t('SettingsText', 'Application ID'),
                ];
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
        $i18n->translations['SettingsText*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/system/messages',
            'fileMap' => [
                'SettingsText' => 'SettingsText.php',
            ],
        ];
    }




}
