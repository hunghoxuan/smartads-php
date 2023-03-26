<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "app_user_device".
 *

 * @property integer $id
 * @property integer $user_id
 * @property string $ime
 * @property string $gcm_id
 * @property integer $type
 * @property integer $status
 */
class AppUserDeviceBase extends BaseModel //\yii\db\ActiveRecord
{

// id, user_id, ime, gcm_id, type, status
    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_IME = 'ime';
    const COLUMN_GCM_ID = 'gcm_id';
    const COLUMN_TYPE = 'type';
    const COLUMN_STATUS = 'status';

    /**
    * @inheritdoc
    */
    public $tableName = 'app_user_device';

    public static function tableName()
    {
        return 'app_user_device';
    }



    /**
     * @inheritdoc
     * @return AppUserDeviceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserDeviceQuery(get_called_class());
    }
}
