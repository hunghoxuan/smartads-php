<?php

namespace backend\modules\smartscreen\models;

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
 * This is the customized model class for table "smartscreen_scripts".
 */
class SmartscreenScriptsAPI extends SmartscreenScripts{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'name', 'Logo', 'TopBanner', 'BotBanner', 'ClipHeader', 'ClipFooter', 'ScrollText', 'Clipnum', 'Clip1', 'Clip2', 'Clip3', 'Clip4', 'Clip5', 'Clip6', 'Clip7', 'Clip8', 'Clip9', 'Clip10', 'Clip11', 'Clip12', 'Clip13', 'Clip14', 'CommandNumber', 'Line1', 'Line2', 'Line3', 'Line4', 'Line5', 'Line6', 'Line7', 'Line8', 'Line9', 'Line10', 'Line11', 'Line12', 'Line13', 'Line14', 'Line15', 'Line16', 'scripts_content', 'scripts_file', 'ReleaseDate', 'is_active', ];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
