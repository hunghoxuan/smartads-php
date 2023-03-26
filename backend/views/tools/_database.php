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
$tables = FHtml::getApplicationTables(false);

?>

<?= FHtml::showMessage(!empty($message) ? $message : '', null) ?>

    <div class="col-md-12">
        <div class="">
            Database: <b> <?= \common\components\FModel::currentDatabaseName() ?> </b>.  Tables: <b><?= count($tables) ?></b>. Connection: <?= \common\components\FConfig::getConfigDsn(FHtml::currentDbName()) ?>.

        </div>
        <div class="col-md-12 form-actions pull-right">
            <button type="submit" name="action" value="download_database" class="btn btn-success">
                Export DB (SQL)
            </button>
            <button type="submit" name="action" value="download_database:create" class="btn btn-default">
                Download Structure (SQL)
            </button>
            <button type="submit" name="action" value="download_database:data" class="btn btn-default">
                Download Data (SQL)
            </button>
            <button type="submit" name="action" value="backup_database" class="btn btn-success">
                Backup
            </button>
            Backup Folder: <?= FHtml::getRootFolder() ?>/backup/<?= $application_id ?>
        </div>
        <br/>
        <table class="table table-bordered col-md-12 table-striped">
            <thead>

            <tr>
                <th>Table</th>
                <th class="text-right">Rows</th>
                <th class="text-right">Size</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <?php foreach ($tables as $table => $table_info) { ?>
            <tr style="border-bottom: dashed 1px lightgrey">
                <td class="col-md-3">
                    <h4><?= $table ?></h4>
                </td>
                <td class="col-md-1 text-right">
                    <h4><?= $table_info['rows'] ?></h4>
                </td>
                <td class="col-md-1 text-right">
                    <h4><?= $table_info['size'] ?> Kb</h4>
                </td>
                <td class="col-md-3" style="text-align: center">
                    <button type="submit" name="action" value="backup_table:<?= $table ?>" class="btn btn-success btn-xs">
                        Backup
                    </button>
                    <!--
                    <button type="submit" name="action" value="create_sql:<?= $table ?>" class="btn btn-primary btn-xs">
                        Dump SQL
                    </button>
                    -->
                    <button type="submit" name="action" value="download_sql:<?= $table ?>" class="btn btn-default btn-xs">
                        Download SQL
                    </button>
                    <button type="submit" name="action" value="truncate_table:<?= $table ?>" class="btn btn-warning btn-xs">
                        Truncate
                    </button>
                    <button type="submit" name="action" value="delete_table:<?= $table ?>" class="btn btn-danger btn-xs">
                        Drop
                    </button>
                </td>
            </tr>
            <?php } ?>
        </table>

    </div>
