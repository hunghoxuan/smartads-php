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


/**
 * @OA\Get(
 *     path="/api/home",
 *     summary="API for Dashboard :)",
 *     @OA\Response(response="200", description="Success")
 * )
 */
class HomeAction extends BaseApiAction
{
    public function run()
    {
        $data = [];

        //custom api data
        $fields = ['name', 'image'];
        //$data['cms_blogs1'] = CmsBlogsAPI::find()->select($fields)->where(['is_active' => 1])->all();

        //$data['cms_blogs'] = CmsBlogsAPI::findAll(['is_active' => 1], 'id desc', -1, 1, false, ['name', 'thumbnail' => function ($model) { return FHtml::getFileUrl($model->image, 'cms-blogs'); }, 'description', 'keywords1', 'created_date']);
        $data['settings'] = Settings::findAll(['name', 'description']);

        $out = $this->getResponse($data);

        return $out;
    }

}