<?php
namespace backend\actions;

use backend\models\Settings;
use backend\modules\cms\models\CmsArticleAPI;
use backend\modules\cms\models\CmsBlogs;
use backend\modules\cms\models\CmsBlogsAPI;
use backend\modules\music\models\MusicArtistAPI;
use backend\modules\music\models\MusicPlaylistAPI;
use backend\modules\music\models\MusicSongAPI;
use common\actions\BaseApiAction;
use common\components\FApi;
use common\components\FHtml;


class SettingsAction extends BaseApiAction
{
    public function run()
    {
        $data = [];
        $data['settings'] = Settings::findAll(['name', 'description', 'phone', 'website', 'address', 'version']);
        $out = FApi::getOutputForAPI($data);

        return $out;
    }
}