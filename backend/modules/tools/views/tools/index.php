<?php
use common\components\CrudAsset;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Api';
$moduleTitle = 'Api';
$moduleKey = 'api';

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
$key = FHtml::getRequestParam('key');
?>

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php $form = FActiveForm::begin([
    'id' => 'book-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'edit_type' => $edit_type,
    'display_type' => $display_type,
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

            <div class="col-md-9">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line hidden-print">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?>

                        </span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Database & Email') ?></a>
                            </li>

                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">

                                        <div class="col-md-12">
                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Application', 'attributes' => [
                                                'app_name',
                                                'app_description',
                                                'app_website',
                                                'admin_phone'
                                            ]]); ?>
                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Admin Username: <b>admin</b>' ,
                                                'attributes' => [
                                                    'admin_password', 'admin_email'
                                                ]]); ?>
                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Purchase & License Key', 'attributes' => [
                                                'purchase_site',
                                                'purchase_license',
                                                'purchase_order',
                                                'client_name',  'client_email',
                                            ]]); ?>

                                        </div>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Database', 'attributes' => [
                                                'db_name',
                                                'db_host',
                                                'db_database',
                                                'db_username',
                                                'db_password'
                                            ]]); ?>

                                            <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 2, 'title' => 'Email', 'attributes' => [
                                                'email_host',
                                                'email_username',
                                                'email_password',
                                                'email_port',
                                                'email_encryption',
                                                'email_useTransport',
                                            ]]); ?>
                                        </div>
                                    </div>
                                    <ul>

                                    </ul>
                                    For direct question and support, please send email to the author of application: <a href="mailto:<?= FHtml::getAuthorEmail()?>"><?= FHtml::getAuthor() ?></a> (<?= FHtml::getAuthorEmail()?>) <br/>
                                    <hr/>
                                    <?php if ($canEdit) { ?>
                                        <button class="btn btn-primary">SETUP</button>
                                    <?php } else {?>
                                        <a href="<?=    FHtml::createUrl('site/index') ?>" class="btn btn-default">Continue</a>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="profile-sidebar col-md-3 col-xs-12 hidden-print">
                <?= FHtml::render('_menu_right') ?>
            </div>
        </div>
    </div>
<?php \common\widgets\FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end()  ?>