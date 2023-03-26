<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "CmsBlogs".
*/
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;

$form_Type = $this->params['activeForm_type'];

if (!isset($moduleKey))
    $moduleKey = FHtml::currentController();
$moduleName = $moduleKey;
$moduleTitle = $moduleName;
$modulePath = str_replace('-', '_', $moduleKey);

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\modules\cms\models\CmsBlogs */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
$this->title = FHtml::t($moduleTitle);
$this->params['mainIcon'] = 'fa fa-list';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
} ?>


<?php $form = FActiveForm::begin([
    'id' => 'cms-blogs-form', 'model' => $model,
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'dynamicForm' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);
?>
<div class="form">
    <div class="row">
            <div class="profile-sidebar col-md-3">
                <div class="portlet light">
 <?= FHtml::showImage($model->image, $modulePath, '100%', 0)  ?> <br/><br/>
 <?= FHtml::showImage($model->banner, $modulePath, '100%', 0)  ?>
 <?= FHtml::showImage($model->thumbnail, $modulePath, '100%', 0)  ?>
<div class="margin-top-20">&nbsp;
                        <h4><b><?= $model->name  ?></b></h4>
                                <small class='text-default'><?= $model->overview  ?></small>
                                            </div>
                    <div class="margin-top-20">
                                            </div>
                    <div class="margin-top-20">
                        <?= FHtml::showLabel('cms_blogs', 'cms_blogs', 'type', $model->type) ?>
<?= FHtml::showLabel('cms_blogs', 'cms_blogs', 'status', $model->status) ?>
<?= FHtml::showLabel('cms_blogs', 'cms_blogs', 'lang', $model->lang) ?>
                    </div>

                    <!--
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li class="active">
                                <a href="#">
                                    <i class="icon-settings"></i> Edit Detail </a>
                            </li>
                        </ul>
                    </div>-->
                </div>
                <!-- END MENU -->
                <div class="portlet light">
                     <div class="row list-separated profile-stat">
                         <div class="col-md-4 col-sm-4 col-xs-6"><?= FHtml::showField('Views', $model->count_views, FHtml::SHOW_NUMBER) ?> </div><div class="col-md-4 col-sm-4 col-xs-6"><?= FHtml::showField('Comments', $model->count_comments, FHtml::SHOW_NUMBER) ?> </div><div class="col-md-4 col-sm-4 col-xs-6"><?= FHtml::showField('Likes', $model->count_likes, FHtml::SHOW_NUMBER) ?> </div><div class="col-md-4 col-sm-4 col-xs-6"><?= FHtml::showField('Rates', $model->count_rates, FHtml::SHOW_NUMBER) ?> </div></div>
                    <div>
                        <div class="row list-separated profile-stat">
                            <div class="col-md-6">
                                <?= FHtml::showField('Created', FHtml::getFieldValue($model, 'created_date'), FHtml::SHOW_DATE) ?>                                </div>
                            <div class="col-md-6">
                                <?= FHtml::showField(' ', $model->created_user, FHtml::SHOW_USER) ?>                                </div>
                        </div>
                        <div class="row list-separated profile-stat">
                            <div class="col-md-6">
                                <?= FHtml::showField('Modified', $model->modified_date, FHtml::SHOW_DATE) ?>                                </div>
                            <div class="col-md-6">
                                <?= FHtml::showField(' ', $model->modified_user, FHtml::SHOW_USER) ?>                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info')?></a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Uploads')?></a>
                            </li>
                            <li>
                                <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Attributes')?></a>
                            </li>
                                                        </ul>
                    </div>
                    <div class="portlet-body form">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                            <?php
                                            foreach ($fields as $field) {
                                                echo \common\widgets\formfield\FieldEdit::widget([
                                                    'model' => $model, 'form' => $form, 'attribute' => $field->name, 'lookup' => !empty($field->lookup) ? '@' . $field->lookup : '', 'editor' => $field->editor,
                                                    'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath]);
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                        <?= FormObjectFile::widget( [
                                            'model' => $model, 'form' => $form,
                                            'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => 'object-file'
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="tab-pane row" id="tab_1_3">
                                        <div class="col-md-12">
                                            <?= FormObjectAttributes::widget( [
                                            'model' => $model, 'form' => $form,
                                            'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
                                            ]) ?>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="tab-pane row" id="tab_1_p">
                                        <div class="col-md-12">
                                                                                    </div>
                                    </div>
                                    -->

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <?php                $type = FHtml::getFieldValue($model, 'type');
                if (isset($modelMeta) && !empty($type))
                    echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
                  ?>
                <?php                    $display_actions = !isset($display_actions) ? true : false;
                    if ($display_actions)
                        echo $this->render('_detail_actions', ['model' => $model, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
                  ?>
            </div>
    </div>
</div>
<?php FActiveForm::end(); ?>
