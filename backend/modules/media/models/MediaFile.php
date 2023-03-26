<?php

namespace backend\modules\media\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "media_file".
 */
class MediaFile extends MediaFileBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = ['image','file','file_path','file_type','file_size','file_duration',];

    public $order_by = 'sort_order asc,is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];





    public function prepareCustomFields() {
        parent::prepareCustomFields();

    }

    public static function getLookupArray($column = '') {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }


}
