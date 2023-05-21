<?php

use common\components\FHtml;
use common\components\Helper;
use unclead\multipleinput\MultipleInput;
use yii\widgets\Pjax;

?>

<?php
$object_type = isset($object_type) ? $object_type : 'object_relation';
$related_field = isset($related_field) ? $related_field : 'artist_singer_id';

if ($object_type == 'object_relation')
    $related_condition = ['object_id' => $model->id, 'object_type' => FHtml::getTableName($model), 'object2_type' => $object_type];
else
    $related_condition = $related_field == 'object_id' ? ['object_id' => $model->id, 'object_type' => FHtml::getTableName($model)] : [$related_field => $model->id];

$data = isset($data) ? $data : FHtml::getDataProvider($object_type, $related_condition);

$related_model = FHtml::createModel($object_type); // create new object model

if (empty($field_name))
    $field_name = \yii\helpers\BaseInflector::camelize(FHtml::getTableName($model)) . \yii\helpers\BaseInflector::camelize($object_type);

$grid_id = isset($grid_id) ? $grid_id : 'crud-datatable' . $field_name;
$pjax_container = isset($pjax_container) ? $pjax_container : $grid_id . '-pjax';

?>
<div class="row">

    <?php if ($canEdit) { ?>
        <div class="portlet light">
            <div class="portlet-title tabbable-line hidden-print">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-blue-madison uppercase">
                        <?= FHtml::t('common', 'Create ' . $field_name) ?> </span>
                </div>
                <div class="tools pull-right">
                    <a href="#" class="collapse"></a>
                </div>

            </div>
            <div class="portlet-body form">
                <div class="form">
                    <div class="form-body no-padding">
                        <div class="tab-content no-padding">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">

                                    <?php $form = \common\widgets\FActiveForm::begin([
                                        'id' => "$object_type-form",
                                        'type' => \kartik\form\ActiveForm::TYPE_HORIZONTAL, //ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
                                        'readonly' => !$canEdit, // check the Role here
                                        'enableClientValidation' => true,
                                        'enableAjaxValidation' => false,
                                        'options' => [
                                            'enctype' => 'multipart/form-data'
                                        ]
                                    ]);
                                    ?>
                                    <?php echo \common\widgets\FFormTable::widget(['model' => $related_model, 'form' => $form, 'columns' => 1, 'attributes' => [
                                        'name' => ['value' => $form->fieldNoLabel($related_model, 'name')->textInput()],
                                        'description' => ['value' => $form->fieldNoLabel($related_model, 'description')->textarea(['rows' => 3])],
                                        'content' => ['value' => $form->fieldNoLabel($related_model, 'content')->textarea(['rows' => 3])],
                                        'thumbnail' => ['value' => $form->fieldNoLabel($related_model, 'thumbnail')->image()],
                                        'image' => ['value' => $form->fieldNoLabel($related_model, 'image')->image()],
                                    ]]); ?>

                                    <?php \common\widgets\FActiveForm::end(); ?>
                                    <?php echo FHtml::buttonCreateAjax($object_type, false, false, $pjax_container);
                                    FHtml::registerPlusJS($object_type, ['name', 'description', 'thumbnail', 'image'], $pjax_container, '{object}[{column}]', $related_condition);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    <?php } else { ?>

    <?php } ?>
    <div class="col-md-12">
        <?= \common\widgets\FGridView::widget([
            'id' => $grid_id,
            'dataProvider' => $data,
            'object_type' => $object_type,
            //'display_type' => FHtml::DISPLAY_TYPE_WIDGET,
            'pjax' => true,
            'form_enabled' => false,
            'filterEnabled' => false,
            'default_fields' => $related_condition,
            'layout' => '{items}{summary}{pager}',
            'edit_type' => FHtml::EDIT_TYPE_INLINE,
            'columns' => [
                [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
                    'class' => 'kartik\grid\SerialColumn',

                ],
                //                [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
                //                    'class' => 'kartik\grid\DataColumn',
                //                    'label' => FHtml::t('common', 'Image'),
                //                    'format' => 'image',
                //                    'attribute' => 'thumbnail',
                //                ],
                [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
                    'class' => 'kartik\grid\DataColumn',
                    'label' => FHtml::t('common', 'Name'),
                    'attribute' => 'name',
                    'value' => function ($model) {
                        return FHtml::showObjectPreview($model, ['image', 'name', 'description', 'status'], true);
                    },
                ],
                $canEdit === false ? [] : [
                    'class' => 'common\widgets\FActionColumn',
                    'dropdown' => 'ajax', // Dropdown or Buttons
                    'actionLayout' => '{delete}',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '120px'
                ]
            ]
        ]) ?>
    </div>
</div>