<?php
namespace backend\actions;

use common\components\FBackup;
use common\components\FEmail;
use common\components\FFile;
use Yii;
use common\components\FHtml;

class ZipFileAction extends BaseAction
{
    public function run()
    {
        $folder = FHtml::getRequestParam('folder');
        $save_to = FHtml::getRequestParam(['save_to']);
        $deleted_after_zip = FHtml::getRequestParam(['deleted_after_zip'], false);
        $excluded = FHtml::getRequestParam(['excluded'], []);

        if (is_file($folder))
            return FFile::zipFileDirect($folder, $deleted_after_zip);

        return FFile::zipFolderDirect($folder, $save_to, [], $deleted_after_zip);
    }
}
