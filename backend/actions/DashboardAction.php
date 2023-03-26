<?php
namespace backend\actions;

use backend\models\Settings;
use backend\modules\cms\models\CmsArticleAPI;
use backend\modules\cms\models\CmsBlogs;
use backend\modules\cms\models\CmsBlogsAPI;
use backend\modules\music\models\MusicArtistAPI;
use backend\modules\music\models\MusicPlaylistAPI;
use backend\modules\music\models\MusicSongAPI;
use backend\modules\system\models\SettingsApiAPI;
use common\actions\BaseApiAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FError;
use common\components\FHtml;


class DashboardAction extends HomeAction
{
    public function run()
    {
        return parent::run();
    }

}