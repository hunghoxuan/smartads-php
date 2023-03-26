<?php
namespace backend\actions;

use common\components\FEmail;
use Yii;
use common\components\FHtml;

class FileDownloadAction extends BaseAction
{
    public function run()
    {
        $file = FHtml::getRequestParam(['file']);
        return FHtml::downloadFile($file);
    }
}
