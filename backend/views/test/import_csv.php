<?php

use common\components\FHtml;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$folders =
    [
        '/Volumes/DATA/googledrive/Moza Solutions/Projects/_OUTSOURCE/2019-Johnson-Thailand/development/master-table',
        '/Volumes/DATA/googledrive/Moza Solutions/Projects/_OUTSOURCE/2019-Johnson-Thailand/development/sc-profile'
    ];

$files = [];
foreach ($folders as $folder) {
    $files = array_merge($files, \common\components\FFile::listFiles($folder, true));
}

$files = ['/Volumes/DATA/googledrive/Moza Solutions/Projects/_OUTSOURCE/2019-Johnson-Thailand/development/sc-profile/User.csv'];

foreach ($files as $file) {
    //$file, $table = '', $columns = [], $first_row = 1, $last_row = -1, $default_values = [], $key_fields = ['id', 'code'], $importData = true
    \common\components\FBackup::import($file, 'usersc', [], 1, -1, [], [], true);
}

?>

