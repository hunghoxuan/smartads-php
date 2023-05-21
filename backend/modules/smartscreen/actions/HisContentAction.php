<?php

namespace backend\modules\smartscreen\actions;

use backend\models\ObjectAttributes;
use backend\models\User;
use backend\modules\smartscreen\Smartscreen;
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

/*
 * This is the customized model class for table "backend\models\MusicArtist".
 */

class HisContentAction extends BaseAction
{
    public function run()
    {
        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $models = Smartscreen::getQueueModels('smartscreen_queue');

        $result = FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
        $result['data'] = $models;

        $result['current_time'] = time();

        $settings = Smartscreen::getSettings();
        $result['settings'] = $settings;

        return $result;
    }
}
