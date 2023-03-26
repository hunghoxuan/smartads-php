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
 * This is the model class for table "object_actions".
 *

 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $name
 * @property string $old_content
 * @property string $content
 * @property string $action
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectActionsBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const ACTION_COMMENT = 'comment';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_APPROVE = 'approve';
    const ACTION_REJECT = 'reject';
    const ACTION_FEEDBACK = 'feedback';

    /**
    * @inheritdoc
    */
    public $tableName = 'object_actions';

    public static function tableName()
    {
        return 'object_actions';
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
                    'object_id' => 'Object ID',
                    'object_type' => 'Object Type',
                    'name' => 'Name',
                    'old_content' => 'Old Content',
                    'content' => 'Content',
                    'action' => 'Action',
                    'is_active' => 'Is Active',
                    'created_date' => 'Created Date',
                    'created_user' => 'Created User',
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
        $i18n->translations['ObjectActions*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectActions' => 'ObjectActions.php',
            ],
        ];
    }



}
