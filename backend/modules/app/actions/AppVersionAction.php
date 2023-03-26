<?php
namespace backend\mobules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppVersion;
use common\components\FEmail;
use common\components\FHtml;
use Yii;

class AppVersionAction extends BaseAction
{
    public function run()
    {
        $current_version = FHtml::getRequestParam('current_version');
        $upgrade_version = FHtml::getRequestParam('upgrade_version');
        $os = FHtml::getRequestParam('current_os');

        if (FHtml::isTableExisted('app_version')) {
            $condition = '(is_default = 1)';
            if (!empty($upgrade_version))
                $condition .= " OR (version_code = $upgrade_version)";
            else if (!empty($current_version))
                $condition .= " OR (version_code > $current_version)";

            $model = AppVersion::findOne($condition);
            if (isset($model)) {
                $file_path = FHtml::getFileUrl($model->file, 'app-version');
                return $file_path;
            }
        } else {
            $file_path = FHtml::setting('app_file');
            return $file_path;
        }
    }
}
