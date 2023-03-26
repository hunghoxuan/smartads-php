<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_activity".
 *
 * @property integer $id
 * @property string $object_id
 * @property string $object_type
 * @property string $type
 * @property integer $user_id
 * @property string $user_type
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class ObjectActivityBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_LIKE = 'like';
    const TYPE_SHARE = 'share';
    const TYPE_FAVOURITE = 'favourite';
    const TYPE_RATE = 'rate';
    const USER_TYPE_APP_USER = 'app_user';
    const USER_TYPE_USER = 'user';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_activity';

    public static function tableName()
    {
        return 'object_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'type', 'user_id', 'user_type', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id', 'object_type', 'type', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['object_id', 'object_type', 'type', 'user_type', 'application_id'], 'string', 'max' => 100],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectActivity', 'ID'),
            'object_id' => FHtml::t('ObjectActivity', 'Object ID'),
            'object_type' => FHtml::t('ObjectActivity', 'Object Type'),
            'type' => FHtml::t('ObjectActivity', 'Type'),
            'user_id' => FHtml::t('ObjectActivity', 'User ID'),
            'user_type' => FHtml::t('ObjectActivity', 'User Type'),
            'created_date' => FHtml::t('ObjectActivity', 'Created Date'),
            'modified_date' => FHtml::t('ObjectActivity', 'Modified Date'),
            'application_id' => FHtml::t('ObjectActivity', 'Application ID'),
        ];
    }


}