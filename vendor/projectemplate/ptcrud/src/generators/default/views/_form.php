<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator projectemplate\ptcrud\generators\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$has_switch = false;
$has_editor = false;

$tableSchema = $generator->tableSchema;

foreach ($tableSchema->columns as $column) :
    if($column->dbType == 'tinyint(1)'){
        $has_switch = true;
        break;
    }
endforeach;

foreach ($tableSchema->columns as $column) :
    if($column->type == "text"){
        $has_editor = true;
        break;
    }
endforeach;

$image_fields = array(
    'thumbnail',
    'image',
    'icon',
    'logo',
    'avatar',
    'cover'
);
$file_fields = array(
    'attachment',
);
$datetime_fields = array(
    'created_date',
    'modified_date'
);

$urlParams = $generator->generateUrlParams();
//use kartik\switchinput\SwitchInput;

echo "<?php\n"; ?>

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;

<?php if($has_switch): ?>
use kartik\checkbox\CheckboxX;
<?php endif; ?>
<?php
$columnsNames= $generator->getColumnNames();
$check_file = array_intersect($file_fields, $columnsNames);
$check_image = array_intersect($image_fields, $columnsNames);

if (count($check_file)!=0 || count($check_image) != 0) { ?>
use kartik\file\FileInput;
<?php } ?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

<?php if($has_editor): ?>
$kcfOptions = array_merge(\iutbay\yii2kcfinder\KCFinder::$kcfDefaultOptions, [
    'uploadURL' => Yii::getAlias('@web') . '/upload/editor',
    'access' => [
        'files' => [
            'upload' => true,
            'delete' => false,
            'copy' => false,
            'move' => false,
            'rename' => false,
        ],
        'dirs' => [
            'create' => true,
            'delete' => false,
            'rename' => false,
        ],
    ],
]);
Yii::$app->session->set('KCFINDER', $kcfOptions);
<?php endif; ?>
?>

<?= "<?php" ?> if (!Yii::$app->request->isAjax) {
    $this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
    $this->params['breadcrumbs'][] = ($model->isNewRecord) ? Yii::t('common', 'title.create') : Yii::t('common', 'title.update');
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
}<?= " ?>" ?>

<?= '<?php if (Yii::$app->request->isAjax) { ?>' . "\n" ?>

    <?= "<?php " ?>$form = FActiveForm::begin(<?= "\n" ?>
        [
            'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form',
            'type' => $this->params['activeForm_type'],//ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => false, // check the Role here
            'options' => [
                //'class' => 'form-horizontal',
            ]
        ]); ?>

    <input type="hidden" id="saveType" name="saveType">

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
    <?= '<?php ' ?>FActiveForm::end(); ?><?= "\n" ?>

<?= '<?php } else { ?>' . "\n" ?>

    <div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
        <div class="<?= "<?=" ?> $this->params['portletStyle'] <?= "?>" ?>">
            <div class="portlet-title hidden-print">
                <div class="caption-title uppercase font-dark">
                    <span class="caption-subject bold uppercase">
                        <i class="<?= "<?php " ?>echo $this->params['mainIcon'] <?= "?>" ?>"></i><?= "<?= " ?><?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> <?= "?>\n" ?>
                    </span>
                    <span class="caption-helper"><?= "<?=" ?> ($model->isNewRecord) ? Yii::t('common', 'title.create') : Yii::t('common', 'title.update') <?= "?>" ?></span>
                </div>
                <div class="tools">
                    <a href="#" class="collapse"></a>
                    <a href="#" class="fullscreen"></a>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body form">
                <?= "<?php " ?>$form = FActiveForm::begin([
                    'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form',
                    'type' => $this->params['activeForm_type'],//ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
                    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
                    'staticOnly' => false, // check the Role here
                    'readonly' => false, // check the Role here
                    'options' => [
                        //'class' => 'form-horizontal',
<?php if (count($check_file)!=0 || count($check_image) != 0) : ?>
                        <?= "'enctype' => 'multipart/form-data'\n" ?>
<?php endif; ?>
                    ]
                ]);
                <?= "?>" ?><?= "\n" ?>
                <div class="form">
                    <div class="form-body">
<?php foreach ($generator->getColumnNames() as $attribute) : ?>
<?php if(in_array($attribute, $safeAttributes)) : ?>
<?php if(in_array($attribute, $file_fields)) { ?>
                        <?= "<?= " ?>$form->field($model, '<?= $attribute ?>_file')->widget(FileInput::classname(),
                            [
                                'options' => [
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'previewFileType' => 'any',
                                    'showRemove' => false,
                                    'showUpload' => false
                                ]
                            ]); <?= "?>\n\n" ?>
<?php } elseif(in_array($attribute, $image_fields)) { ?>
                        <?= "<?= " ?>$form->field($model, '<?= $attribute ?>_file')->widget(FileInput::classname(),
                            [
                                'options' => [
                                    'multiple' => false,
                                    'accept' => 'image/*'
                                ],
                                'pluginOptions' => [
                                    'previewFileType' => 'image',
                                    'showRemove' => false,
                                    'showUpload' => false
                                ]
                            ]); <?= "?>\n\n" ?>
<?php } else { ?>
<?php if(!in_array($attribute, $datetime_fields)) { ?>
                        <?= "<?= " ?><?= $generator->generateActiveFieldAdvanced($attribute) ?> <?= "?>\n\n" ?>
<?php } ?>
<?php } ?>
<?php  endif; ?>
<?php  endforeach; ?>
                    </div>
                    <div class="form-actions">
                        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?= "<?php " ?>if (!$model->isNewRecord) { <?= "?>\n" ?>
                            <?= "<?= " ?>Html::a(Yii::t('common', 'Delete'), ['delete', <?= $urlParams ?>], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]); ?>
                            <?= "<?= " ?>Html::a(Yii::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                        <?= "<?php } else { ?>\n" ?>
                            <?= "<?= " ?>Html::a(Yii::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                        <?= "<?php } ?>\n" ?>
                    </div>
                </div>
             <?= "   <?php " ?>FActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?= '<?php } ?>' ?>
