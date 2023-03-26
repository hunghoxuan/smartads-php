<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use common\widgets\FActiveForm;


$form_Type = $this->params['activeForm_type'];

$moduleName = 'ObjectType';
$moduleTitle = 'Object Type';
$moduleKey = 'object-type';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$object_type = FHtml::getRequestParam('id');

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectType */
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
<?php if (Yii::$app->request->isAjax) { ?>

<?php $form = ActiveForm::begin(
['id' => 'object-type-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => false, // check the Role here
'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
]]); ?>

<input type="hidden" id="saveType" name="saveType">

    <?=  //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'object_type')->input(['disabled' => true]) ?>

    <?=  //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=  //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:  
$form->field($model, 'sort_order')->widget(\kartik\widgets\TouchSpin::className(), ['pluginOptions' => [ 'initval' => 1, 'min' => 0, 'max' => 10000000, 'step' => 1, 'decimals' => 0,  'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus','prefix' => '', 'postfix' => '']]) ?>

    <?=  //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), []) ?>

   <?php ActiveForm::end(); ?>


<?php } else { ?>

            <?php $form = FActiveForm::begin([
            'id' => 'object-type-form',
            'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => !$canEdit, // check the Role here
            'options' => [
                //'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data'
            ]
            ]);
             ?>


            <div class="form">
                <div class="row">

                        <div class="col-md-12">
                            <div class="portlet light">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison uppercase"> <b><?= FHtml::t('common', $object_type) ?> </b> / <?= FHtml::t('common', $moduleTitle) ?></span>
                                    </div>
                                    <div class="tools pull-right">
                                        <a href="#" class="fullscreen"></a>
                                        <a href="#" class="collapse"></a>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <?php if (!empty($object_type) && FHtml::frameworkVersion() == 'business') { ?>
                                            <li class="<?= FHtml::currentController() == 'object-type' ? 'active' : '' ?>">
                                                <a href="<?= FHtml::createUrl('system/object-type/update', ['id' => $object_type]) ?>"><?= FHtml::t('common', 'Info') ?></a>
                                            </li>
                                        <?php } ?>
                                        <?php if (!empty($object_type) && FHtml::frameworkVersion() == 'business') { ?>
                                            <li class="<?= FHtml::currentController() == 'settings-schema' ? 'active' : '' ?>">
                                                <a href="<?= FHtml::createUrl('system/settings-schema/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Schema') ?></a>
                                            </li>
                                        <?php } ?>
                                        <li class="<?= FHtml::currentController() == 'object-setting' ? 'active' : ''  ?>">
                                            <a href="<?= FHtml::createUrl('object-setting/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Settings') ?></a>
                                        </li>
                                        <li class="<?= FHtml::currentController() == 'object-category' ? 'active' : '' ?>">
                                            <a href="<?= FHtml::createUrl('object-category/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Categories') ?></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="body">
                                    <div class="form">
                                        <div class="form-body">
                                            <div class="tab-content">
                                                <div class="tab-pane active row" id="tab_1_1">
                                                    <div class="col-md-12">
                                                               <?=  //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'object_type')->textInput(['disabled' => true, 'readonly' => true]) ?>

       <?=  //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'name')->textInput(['maxlength' => true]) ?>

       <?=  //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
$form->field($model, 'is_active')->boolean()?>

                                                    </div>
                                                </div>
                                                <div class="tab-pane row" id="tab_1_2">
                                                    <div class="col-md-12">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light'; ?>">
                                <?= \kartik\grid\GridView::widget([
                                    'id' => 'crud-datatable',
                                    'dataProvider' => new \yii\data\ActiveDataProvider(['query' => \backend\models\Settings::find()->where("metaKey like '" . FHtml::getRequestParam('id') . "%'")]),
                                    'filterModel' => null,
                                    'columns' => require(__DIR__ . '/_columns_simple.php'),
                                    'striped' => false,
                                    'condensed' => false,
                                    'responsive' => true,
                                    'bordered' => false,
                                    'layout' => "{items}",
                                ]) ?>
                            </div>
                            <div class="">
                                <?php if ($canEdit) { echo Html::submitButton($model->isNewRecord ? FHtml::t('common', 'Create')
                                : FHtml::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' :
                                'btn btn-primary']);} ?>
                                <?php  if (!$model->isNewRecord && $canDelete) {?>
                                <?=  Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->object_type], [
                                'class' => 'btn btn-danger pull-right',
                                'data' => [
                                'confirm' => FHtml::t('common', 'Are you sure to delete?'),
                                'method' => 'post',
                                ],
                                ]); ?>
                                <?=  Html::a(FHtml::t('common', 'Reset'), ['renew', 'id' => $model->object_type], [
                                    'class' => 'btn btn-success pull-right',
                                    'data' => [
                                        'confirm' => FHtml::t('common', 'Are you sure to delete?'),
                                        'method' => 'post',
                                    ],
                                ]);
                                ?>
                                    <?=  Html::a(FHtml::t('common', 'Empty'), ['empty', 'id' => $model->object_type], [
                                        'class' => 'btn btn-warning pull-right',
                                        'data' => [
                                            'confirm' => FHtml::t('common', 'Are you sure to delete?'),
                                            'method' => 'post',
                                        ],
                                    ]);
                                    ?>
                                <?=  Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                                <?php } else { ?>
                                <?=  Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                                <?php } ?>                            </div>
                        </div>
                </div>
            </div>
               <?php FActiveForm::end(); ?>

<?php } ?>


