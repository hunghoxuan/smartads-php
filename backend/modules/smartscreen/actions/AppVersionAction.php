<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\app\models\AppVersion;
use common\actions\BaseApiAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FEmail;
use common\components\FHtml;
use Yii;

class AppVersionAction extends BaseApiAction
{
    public function run()
    {
        $current_version = FHtml::getRequestParam(['current_version']);
        $upgrade_version = FHtml::getRequestParam(['upgrade_version', 'versionCode']);
        $platform = strtolower(FHtml::getRequestParam('platform'));
        $ime = FHtml::getRequestParam(['ime']);
        $id = FHtml::getRequestParam(['id']);
        $type = FHtml::getRequestParam(['type']);
        $package_name = FHtml::getRequestParam(['package_name', 'packageName']);
        $getOne = true;

        $result = [];

        $message = '';

        if (FHtml::isTableExisted('app_version')) {
            $condition = '(is_active = 1)';
            if (!empty($id) && is_numeric($id)) {
                $condition = $condition . ' and id = ' . $id;
                $getOne = true;
            } else if (!empty($id) && in_array($id, ['all', '*'])) {
                $getOne = false;
            }
            if (!empty($platform)) {
                $condition = $condition . " AND (platform = '$platform')";
            }

            if (!empty($package_name)) {
                $condition = $condition . " AND (package_name = '$package_name')";
            }

            if ($getOne) {
                $models = AppVersion::find()->where($condition)->limit(1)->orderBy('is_default desc, package_version DESC')->one();
                if ($models != null)
                    $models = [$models];
                $model = $models[0];
            } else
                $models = AppVersion::find()->where($condition)->orderBy('is_default desc, package_version DESC')->all();

            if ($getOne) {

                $file_path = empty($model) ? '' : FHtml::getFileUrl($model->file, 'app-version');

                if (!empty($file_path)) {
                    $result['id'] = $model->id;
                    $result['file'] = $file_path;
                    $result['name'] = $model->name;
                    $result['description'] = $model->description;
                    $result['version'] = $model->package_version;
                    $result['package_name'] = $model->package_name;
                    $result['history'] = $model->history;
                    $result['platform'] = $model->platform;
                    $result['modified_date'] = $model->modified_date;
                } else {
                    $message = "File not found";
                }
            } else {

                for ($i = 0; $i < count($models); $i++) {
                    $model = $models[$i];
                    $file_path = FHtml::getFileUrl($model->file, 'app-version');
                    if (!empty($file_path)) {
                        $item = [];
                        $item['id'] = $model->id;
                        $item['url'] = $file_path;
                        $item['title'] = $model->name;
                        $item['description'] = $model->description;
                        $item['type'] = $model->platform;
                        $item['version'] = $model->package_version;
                        $item['package_name'] = $model->package_name;
                        $item['modified_date'] = $model->modified_date;

                        $result[] = $item;
                    }
                }
            }
        } else {
            $key = 'app_file';
            $file_path = FHtml::setting("$key" . "_file");
            if (!empty($file_path)) {
                $result['file'] = $file_path;
                $result['description'] = FHtml::setting("$key" . "_description");
                $result['version'] = FHtml::setting("$key" . "_version");
                $result['package_name'] = FHtml::setting("$key" . "_package_name");
                $result['history'] = FHtml::setting("$key" . "_history");
                $result['modified_date'] = FHtml::Today();
            } else {
                $message = "File not found";
            }
        }

        if (!empty($result))
            return FApi::getOutputForAPI($result, FConstant::SUCCESS, '', ['code' => 200]);
        else
            return FApi::getOutputForAPI('', FConstant::ERROR, $message, ['code' => 205]);
    }
}
