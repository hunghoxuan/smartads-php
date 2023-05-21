<?php

namespace backend\modules\users\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "user_feedback".
 */
class UserFeedback extends UserFeedbackBase //\yii\db\ActiveRecord
{
    const LOOKUP = [
        'type' => [
            ['id' => UserFeedback::TYPE_QUESTION, 'name' => 'Question'],
            ['id' => UserFeedback::TYPE_FEEDBACK, 'name' => 'Feedback'],
            ['id' => UserFeedback::TYPE_REPORT, 'name' => 'Report'],
        ],
        'status' => [
            ['id' => UserFeedback::STATUS_NEW, 'name' => 'New'],
            ['id' => UserFeedback::STATUS_RECEIVED, 'name' => 'Received'],
            ['id' => UserFeedback::STATUS_PROCESSING, 'name' => 'Processing'],
            ['id' => UserFeedback::STATUS_PENDING, 'name' => 'Pending'],
            ['id' => UserFeedback::STATUS_CLOSED, 'name' => 'Closed'],
        ],
    ];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column)
    {
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



    // Lookup Object: user\n
    public $user;
    public function getUser()
    {
        if (!isset($this->user))
            $this->user = FHtml::getModel('app_user', '', $this->user_id, '', false);

        return $this->user;
    }
    public function setUser($value)
    {
        $this->user = $value;
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

        $this->user = self::getUser();
    }


    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }
}
