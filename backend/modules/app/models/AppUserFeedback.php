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
 * This is the customized model class for table "app_user_feedback".
 */
class AppUserFeedback extends AppUserFeedbackBase //\yii\db\ActiveRecord
{
    const LOOKUP = [        'type' => [
		['id' => AppUserFeedback::TYPE_QUESTION, 'name' => 'Question'],
 	['id' => AppUserFeedback::TYPE_FEEDBACK, 'name' => 'Feedback'],
 	['id' => AppUserFeedback::TYPE_REPORT, 'name' => 'Report'],
 ],
        'status' => [
		['id' => AppUserFeedback::STATUS_NEW, 'name' => 'New'],
 	['id' => AppUserFeedback::STATUS_RECEIVED, 'name' => 'Received'],
 	['id' => AppUserFeedback::STATUS_PROCESSING, 'name' => 'Processing'],
 	['id' => AppUserFeedback::STATUS_PENDING, 'name' => 'Pending'],
 	['id' => AppUserFeedback::STATUS_CLOSED, 'name' => 'Closed'],
 ],
];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column = '') {
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
        
            [['id', 'user_id', 'object_id', 'object_type', 'comment', 'response', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['user_id', 'comment'], 'required'],
            [['response'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['user_id', 'object_id', 'object_type', 'type', 'status', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 4000],
        ];
    }



    // Lookup Object: user\n
    public $user;
    public function getUser() {
        if (!isset($this->user))
        $this->user = FHtml::getModel('user', '', $this->user_id, '', false);

        return $this->user;
    }
    public function setUser($value) {
        $this->user = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->user = self::getUser();
    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
