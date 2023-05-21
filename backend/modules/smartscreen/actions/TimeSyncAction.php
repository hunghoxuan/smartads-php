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

/*
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
