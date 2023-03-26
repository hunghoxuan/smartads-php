<?php
namespace backend\actions;

use common\components\FEmail;
use Yii;
use common\components\FHtml;

class CopyFileAction extends BaseAction
{
    public function run()
    {
        $copy_from = FHtml::getRequestParam('folder');
        $dest = FHtml::getRequestParam('dest');

        $copy_from = FHtml::getFullFileName($copy_from);
        $dest = FHtml::getFullFileName($dest);

        FHtml::copy($copy_from, $dest);
    }
}
