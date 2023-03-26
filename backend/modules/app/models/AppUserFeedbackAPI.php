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
class AppUserFeedbackAPI extends AppUserFeedback{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'user_id', 'object_id', 'object_type', 'comment', 'response', 'type', 'status', ];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
