<?php
use common\components\CrudAsset;
use common\components\FHtml;
use common\widgets\FActiveForm;
use yii\widgets\Pjax;
use common\components\FSystem;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'TOOLS::BACKUP';
$moduleTitle = 'TOOLS::BACKUP';
$moduleKey = 'TOOLS::BACKUP';

$this->title = FHtml::t($moduleTitle);

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only

$model = null;
$application_id = isset($application_id) ? $application_id : FHtml::currentApplicationId();
$ajax = isset($ajax) ? $ajax : true;

$applications_combo = FHtml::getComboArray(FHtml::getApplications());
$tables = FHtml::getApplicationTables();

?>

<?= FHtml::showMessage(!empty($message) ? $message : '', null) ?>

    <div class="col-md-12">
        <h3>DSN: <?= \common\components\FConfig::getConfigDsn(FHtml::currentDbName()) ?> </h3>
        <table class="table table-bordered col-md-12">
            <?php foreach ($tables as $table) { ?>
            <tr style="border-bottom: dashed 1px lightgrey">
                <td>
                    <?= $table ?>
                </td>
                <td style="text-align: right">
                    <button type="submit" name="action" value="backup_table:<?= $table ?>" class="btn btn-success btn-xs">
                        Backup
                    </button>
                    <button type="submit" name="action" value="create_sql:<?= $table ?>" class="btn btn-primary btn-xs">
                        Dump SQL
                    </button>
                    <button type="submit" name="action" value="truncate_table:<?= $table ?>" class="btn btn-warning btn-xs">
                        Clear
                    </button>
                    <button type="submit" name="action" value="delete_table:<?= $table ?>" class="btn btn-danger btn-xs">
                        Delete
                    </button>
                    <button type="submit" name="action" value="download_sql:<?= $table ?>" class="btn btn-default btn-xs">
                        Download SQL
                    </button>
                </td>
            </tr>
            <?php } ?>
        </table>

    </div>
