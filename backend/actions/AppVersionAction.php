<?php
namespace backend\actions;

use backend\modules\app\models\AppVersion;
use common\components\FApi;
use common\components\FConstant;
use common\components\FEmail;
use common\components\FHtml;
use Yii;

class AppVersionAction extends BaseAction
{
    public function run()
    {
        $current_version = FHtml::getRequestParam(['current_version']);
        $upgrade_version = FHtml::getRequestParam(['upgrade_version', 'versionCode']);
        $platform = strtolower(FHtml::getRequestParam('platform'));
        $ime = FHtml::getRequestParam(['ime']);

        $package_name = FHtml::getRequestParam(['package_name', 'packageName']);

        $result = [];

        if (FHtml::isTableExisted('app_version')) {
            $condition = '(is_active = 1)';
            if (!empty($upgrade_version))
                $condition .= " AND (package_version >= $upgrade_version)";
            else if (!empty($current_version))
                $condition .= " AND (package_version > $current_version)";
            else
                $condition .= " AND (is_default = 1)";

            if (!empty($package_name))
                $condition .= " AND (package_name = '$package_name')";

            if (!empty($platform))
                $condition .= " AND (platform = '$platform')";

            //echo $condition;die;
            /** @var AppVersion $model */
            $model = AppVersion::find()->where($condition)->limit(1)->orderBy('package_version DESC')->one();
            if (isset($model)) {
                $file_path = FHtml::getFileUrl($model->file, 'app-version');
                $result['file'] = $file_path;
                $result['description'] = $model->description;
                $result['version'] = $model->package_version;
                $result['package_name'] = $model->package_name;
                $result['history'] = $model->history;
                $result['modified_date'] = $model->modified_date;
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
            }
        }
        if (!empty($result))
            return FApi::getOutputForAPI($result, FConstant::SUCCESS, '', ['code' => 205]);
        else
            return FApi::getOutputForAPI('', FConstant::ERROR, null, ['code' => 205]);
    }
}
