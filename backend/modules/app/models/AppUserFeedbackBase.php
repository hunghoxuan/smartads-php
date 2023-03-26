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
 * This is the model class for table "app_user_feedback".
 *

 * @property integer $id
 * @property string $user_id
 * @property string $object_id
 * @property string $object_type
 * @property string $comment
 * @property string $response
 * @property string $type
 * @property string $status
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class AppUserFeedbackBase extends BaseModel //\yii\db\ActiveRecord
{
    const TYPE_QUESTION = 'Question';
    const TYPE_FEEDBACK = 'Feedback';
    const TYPE_REPORT = 'Report';
    const STATUS_NEW = 'New';
    const STATUS_RECEIVED = 'Received';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_PENDING = 'Pending';
    const STATUS_CLOSED = 'Closed';

    /**
    * @inheritdoc
    */
    public $tableName = 'app_user_feedback';

    public static function tableName()
    {
        return 'app_user_feedback';
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
                    'user_id' => 'User ID',
                    'object_id' => 'Object ID',
                    'object_type' => 'Object Type',
                    'comment' => 'Comment',
                    'response' => 'Response',
                    'type' => 'Type',
                    'status' => 'Status',
                    'created_date' => 'Created Date',
                    'created_user' => 'Created User',
                    'modified_date' => 'Modified Date',
                    'modified_user' => 'Modified User',
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
        $i18n->translations['AppUserFeedback*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/app/messages',
            'fileMap' => [
                'AppUserFeedback' => 'AppUserFeedback.php',
            ],
        ];
    }



}
