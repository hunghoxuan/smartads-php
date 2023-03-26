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
 * This is the model class for table "application".
 *

 * @property integer $id
 * @property string $logo
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $keywords
 * @property string $note
 * @property string $lang
 * @property string $modules
 * @property string $storage_max
 * @property string $storage_current
 * @property string $address
 * @property string $map
 * @property string $website
 * @property string $email
 * @property string $phone
 * @property string $fax
 * @property string $chat
 * @property string $facebook
 * @property string $twitter
 * @property string $google
 * @property string $youtube
 * @property string $copyright
 * @property string $terms_of_service
 * @property string $profile
 * @property string $privacy_policy
 * @property integer $is_active
 * @property string $type
 * @property string $status
 * @property integer $page_size
 * @property string $main_color
 * @property integer $cache_enabled
 * @property string $currency_format
 * @property string $date_format
 * @property string $web_theme
 * @property string $admin_form_alignment
 * @property string $body_css
 * @property string $body_style
 * @property string $page_css
 * @property string $page_style
 * @property string $owner_id
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 */
class ApplicationBase extends \yii\db\ActiveRecord
{
   /**
    * @inheritdoc
    */
    public $tableName = 'application';

    public static function tableName()
    {
        return 'application';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }
}
