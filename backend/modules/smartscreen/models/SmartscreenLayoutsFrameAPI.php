<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "smartscreen_layouts_frame".
 */
class SmartscreenLayoutsFrameAPI extends SmartscreenLayoutsFrameSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['layout_id', 'frame_id',];

    public function fields()
    {
        $fields = $this::COLUMNS_API;

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
