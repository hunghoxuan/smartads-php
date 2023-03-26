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
 * This is the model class for table "auth_group".
 *

 * @property integer $id
 * @property string $name
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AuthGroupBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public $tableName = 'auth_group';

    public static function tableName()
    {
        return 'auth_group';
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
    public function attributeLabels()
    {
        return [
                    'id' => 'ID',
                    'name' => 'Name',
                    'is_active' => 'Is Active',
                    'created_date' => 'Created Date',
                    'modified_date' => 'Modified Date',
                    'application_id' => 'Application ID',
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
        $i18n->translations['AuthGroup*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AuthGroup' => 'AuthGroup.php',
            ],
        ];
    }



}
