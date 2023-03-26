<?php
namespace backend\actions;

use backend\models\Setting;
use common\components\FHtml;
use common\components\Response;
use Yii;
use yii\helpers\Url;


class ShowAppSettingAction extends BaseAction
{
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $data = FHtml::getModels(FHtml::TABLE_SETTINGS);

        return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['code' => 200]);
    }
}
