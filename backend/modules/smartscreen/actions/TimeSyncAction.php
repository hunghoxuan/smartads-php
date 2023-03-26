<?php

namespace backend\modules\smartscreen\actions;

use backend\models\ObjectAttributes;
use backend\models\User;
use backend\modules\system\models\ObjectHash;
use backend\modules\vss\actions\BaseVssAction;
use backend\modules\vss\models\UserAPI;
use backend\modules\vss\Vss;
use common\actions\BaseApiAction;
use backend\actions\BrowseAction;
use common\base\BaseAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;
use yii\helpers\Json;

/**
* Developed by Hung Ho (Steve): hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "backend\models\MusicArtist".
*/
class TimeSyncAction extends BaseAction
{
    public function run()
    {
        $result = FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);

        $result['current_time'] = time();

        return $result;
    }
}


