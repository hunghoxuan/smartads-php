<?php
use common\components\CrudAsset;
use common\components\FHtml;
use common\widgets\FActiveForm;
use yii\widgets\Pjax;
use common\components\FEmail;
use common\components\FFile;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'TOOLS::BACKUP';
$moduleTitle = 'TOOLS::BACKUP';
$moduleKey = 'TOOLS::BACKUP';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only

$model = null;
$application_id = isset($application_id) ? $application_id : FHtml::currentApplicationId();
$ajax = isset($ajax) ? $ajax : true;
$date = date('Y.m.d');

$applications_combo = FHtml::getComboArray(FHtml::getApplications());

?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>

<?php $form = FActiveForm::begin([
    'id' => 'book-form',
    'type' => \kartik\form\ActiveForm::TYPE_HORIZONTAL, //ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => \kartik\form\ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);
?>
<?= FHtml::showMessage(!empty($message) ? $message : '', null) ?>

    <div class="form">
        <div class="row">

            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <b>Select Application: </b>

                            <?= \yii\helpers\Html::dropDownList('application_id', $application_id, $applications_combo, ['id' => $application_id, 'disabled' => FHtml::isApplicationsEnabled() ? false : true]) ?>

                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'All') ?></a>
                            </li>
                            <li class="">
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Tables') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">

                                        <div class="col-md-6">

                                            <h3>Database</h3>
                                            1. Dump database <b></b> <br/>
                                            2. Save to <b>[<?= FHtml::getRootFolder() ?>/backup/<?= $application_id ?>/<?= $date ?>/all.sql] </b> <br/>
                                            <div class="form-actions">
                                                <button type="submit" name="action" value="backup_database" class="btn btn-success">
                                                    Backup
                                                </button>
                                                <button type="submit" name="action" value="download_database" class="btn btn-default">
                                                    Download
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h3>Files</h3>
                                            1. ZIP folder <b>[<?= FHtml::getRootFolder() ?>/applications/<?= $application_id ?>]</b> <br/>
                                            2. Save to <b>[backup/<?= $application_id ?>/<?= $application_id ?>_<?= $date ?>.zip] </b> <br/>
                                            <div class="form-actions">
                                                <button type="submit" name="action" value="backup_files" class="btn btn-success">
                                                    Backup
                                                </button>
                                                <button type="submit" name="action" value="download_files" class="btn btn-default">
                                                    Download
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>BACKUP HISTORY </h3>
                                            <table class="table table-bordered col-md-12">
                                            <?php
                                                $backup = new \common\components\FBackup();
                                                $folders = $backup->getBackupFolders($application_id);
                                                foreach ($folders as $folder => $folder_path) {
                                                    if (!is_file($folder_path . '/all.sql'))
                                                        continue;
                                                    ?>
                                                    <tr style="border-bottom: dashed 1px lightgrey">
                                                        <td class="col-md-6">
                                                            <b><?= $folder ?></b>

                                                        </td>
                                                        <td class="col-md-6">
                                                            <button type="submit" name="action" value="restore_backup:<?= $folder ?>" class="btn btn-primary btn-xs">
                                                                Restore
                                                            </button>
                                                            <button type="submit" name="action" value="download_backup_sql:<?= $folder ?>" class="btn btn-default btn-xs">
                                                                Download SQL
                                                            </button>
                                                            <button type="submit" name="action" value="download_backup_file:<?= $folder ?>" class="btn btn-default btn-xs">
                                                                Download Files
                                                            </button>
                                                            <button type="submit" name="action" value="delete_backup:<?= $folder ?>" class="btn btn-danger btn-xs">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <?php } ?>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane row" id="tab_1_2">
                                        <?= $this->render('_database', ['application_id' => $application_id]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
<?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end() ?>