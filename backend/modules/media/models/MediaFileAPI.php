<?php

namespace backend\modules\media\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * 

 * 
 * This is the customized model class for table "media_file".
 */
class MediaFileAPI extends MediaFile
{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'name', 'image', 'file', 'file_path', 'description', 'file_type', 'file_size', 'file_duration', 'is_active',];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
