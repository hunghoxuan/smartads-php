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
$moduleTitle = 'Database';
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
    <?= FHtml::render('_menu_right') ?>
    <div class="form">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <b><?= FHtml::t('common', $moduleTitle) ?> </b>
                            <?= '';
                            //\yii\helpers\Html::dropDownList('application_id', $application_id, $applications_combo, ['id' => $application_id, 'disabled' => FHtml::isApplicationsEnabled() ? false : true]) ?>

                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Tables') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_2">
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