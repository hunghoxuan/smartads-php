<?php
namespace backend\modules\smartscreen\actions;

use backend\actions\BaseAction;
use backend\modules\app\App;
use backend\modules\app\models\AppFile;
use common\actions\BaseApiAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FEmail;
use common\models\BaseAPIModel;
use Yii;
use common\components\FHtml;
//require_once FHtml::getRootFolder() . '/php/files.php';

class FileDownloadAction extends BaseApiAction
{
    public $is_secured = false;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $file = FHtml::getRequestParam(['file']);
        $file_name = FHtml::getFullFileName($file);

        $ime = FHtml::getRequestParam(['ime']);
        $status = FHtml::getRequestParam(['status']);

        if (empty($ime)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 205]);
        }

        if (!is_file($file_name)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, 'File not found', ['code' => 205]);
        }

        $can_download = true;
        if (!FHtml::isTableExisted('app_file'))
            $can_download = true;
        else {
            $model = AppFile::findOne(['ime' => $ime, 'file_name' => $file_name]);
            if (isset($model) && $model->status == FHtml::STATUS_REJECTED) {
                return FApi::getOutputForAPI('', FConstant::ERROR, FHtml::STATUS_REJECTED, ['code' => 205]);
            }

            if (!isset($model))
                $model = new AppFile();
            else {
                if (!empty($status)) {
                    $model->status = FHtml::STATUS_DONE;
                    $model->download_time = FHtml::Now();
                    $model->application_id = FHtml::currentApplicationId();
                    $model->save();
                }
            }

            $result = [];

            $models = AppFile::findAll(['status' => FHtml::STATUS_PROCESSING]);
            $count = is_array($models) ? count($models) : 0;

            $can_download = $count < 10;
            if ($can_download) {
                $model->ime = $ime;
                $model->file_name = $file_name;
                $model->file_size = filesize($file_name);
                $model->download_time = FHtml::Now();
                $model->status = FHtml::STATUS_PROCESSING;
                $model->application_id = FHtml::currentApplicationId();
                $model->save();
            }

            $can_download = true;
        }

        //have to wait
        if (!$can_download) {
            $int = rand(60, 360);
            $result['download_time'] = strtotime( "+$int second");
            $result['download_file'] = $file;
            $result['can_download'] = $can_download;
        } else {
            $result['download_time'] = time();
            $result['download_file'] = $file;
            $result['can_download'] = $can_download;

        }

        return $result;
    }
}
