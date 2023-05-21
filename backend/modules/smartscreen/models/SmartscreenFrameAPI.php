<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "smartscreen_frame".
 */
class SmartscreenFrameAPI extends SmartscreenFrameSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'name', 'backgroundColor', 'layout_id', 'percentWidth', 'percentHeight', 'marginTop', 'marginLeft', 'contentLayout', 'file', 'content', 'content_id', 'font_size', 'font_color', 'alignment', 'is_active',];

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
