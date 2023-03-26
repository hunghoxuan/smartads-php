<?php
use common\components\FHtml;
use common\components\Helper;
use unclead\multipleinput\MultipleInput;
use yii\widgets\Pjax;
use backend\modules\workflow\Workflow;
?>

<?php
if (empty($object_type) || !isset($model)) {
    echo FHtml::showErrorMessage(FHtml::t('common', "Object Type [$object_type] is not valid or not found"));
    return;
}

if (in_array(FHtml::currentAction(), ['view']))
    $canEdit = false;

$relation_type = isset($relation_type) ? $relation_type : FHtml::RELATION_ONE_MANY;
$relation_field = isset($relation_type) ? $relation_type : 'object_id';
$model_table = FHtml::getTableName($model);

$related_condition = ['object_id' => $model->id, 'object_type' => $model_table];

$data = isset($data) ? $data : FHtml::getDataProvider($object_type, $related_condition);

if (empty($field_name))
    $field_name = \yii\helpers\BaseInflector::camelize(FHtml::getTableName($model)) . \yii\helpers\BaseInflector::camelize($object_type);

$grid_id = isset($grid_id) ? $grid_id : 'crud-datatable' . $field_name . $relation_type;
$pjax_container = isset($pjax_container) ? $pjax_container : $grid_id . '-pjax';
$form_id = str_replace('_', '-', $object_type) . $pjax_container;

$related_model = FHtml::createModel($object_type);
$label = FHtml::t('common', 'Add existing ' . $field_name) . (!empty($relation_type) ? ': ' . FHtml::t('common', \yii\helpers\BaseInflector::camel2words($relation_type)) : '');
$model_camelized_name = \yii\helpers\BaseInflector::camelize($model_table);
$field_camelized_name = \yii\helpers\BaseInflector::camelize($field_name);

$object_fields = !empty($object_fields) ? $object_fields : FHtml::getModelFields($related_model, ['id'], 'preview');
$object_attributes = !empty($object_attributes) ? $object_attributes : [];

if (isset($columns)) {
    $object_columns = $columns;
} else if (!isset($object_columns)) {
    $object_columns = [
        [
            'class' => 'kartik\grid\SerialColumn',
        ],
        'user_id', 'comment', 'status', 'created_date'];
}

$canEdit = isset($canEdit) ? $canEdit : false;
$user_id = isset($user_id) ? $user_id : FHtml::currentUserId();
$status = Workflow::getObjectWorkflowStatus($model_table, $model->id);
$authorized_users = Workflow::getObjectWorkflowUser($model_table, $model->id);
$authorized_user_names = [];

if (!empty($authorized_users)) {
    foreach ($authorized_users as $authorized_user) {
        $authorized_user_names[] = FHtml::showUser($authorized_user);
    }
    $authorized_user_names = implode('; ', $authorized_user_names);
} else {
    $authorized_user_names = '';
}
$canApprove = isset($canApprove) ? $canApprove : Workflow::checkUserCanApproveWorkflow($authorized_users);

//Register Javascript
if ($canApprove) {
    FHtml::registerPlusJS($object_type, $object_fields, [$pjax_container, FHtml::STATUS_APPROVED], '{object}[{column}]', array_merge($related_condition, ['status' => FHtml::STATUS_APPROVED, 'relation_type' => $relation_type, 'user_id' => $user_id, 'object_id' => $model->id, 'object_type' => $model_table]));
    FHtml::registerPlusJS($object_type, $object_fields, [$pjax_container, FHtml::STATUS_REJECTED], '{object}[{column}]', array_merge($related_condition, ['status' => FHtml::STATUS_REJECTED, 'relation_type' => $relation_type, 'user_id' => $user_id, 'object_id' => $model->id, 'object_type' => $model_table]));

    FHtml::registerResetJs($object_type, ['type' => null, $relation_type => 0], $pjax_container);
}

FHtml::registerUnlinkJs($object_type, ['relation_type' => $relation_type, 'object2_type' => $model_table, 'object2_id' => $model->id], $pjax_container);
//Testing `purpose
$status1 = FHtml::getRequestParam('status1');
if (!empty($status1)) {
    \backend\modules\workflow\Workflow::updateObjectWorkflowStatus($model_table, $model->id, $status1);
}

?>
<div class="hidden-print row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-12" style="margin-top:20px; padding-top:20px; border-top:1px dashed lightgrey">
        <b><?= FHtml::t('common', 'Current Status') ?>: <?= !empty($status) ? $status : 'N/A' ?></b>.  <?= FHtml::t('common', 'Being handled by ') ?>: <?= !empty($authorized_user_names) ? $authorized_user_names : 'N/A' ?><br/><br/>
        <?= \common\widgets\FGridView::widget([
            'id' => $grid_id,
            'dataProvider' => $data,
            'object_type' => $object_type,
            'readonly' => !$canEdit,
            'pjax' => true,
            'form_enabled' => false,
            'filterEnabled' => false,
            'showHeader' => true,
            'default_fields' => $related_condition,
            'layout' => '{items}',
            'edit_type' => FHtml::EDIT_TYPE_INLINE,
            'columns' => $object_columns,
        ]) ?>
        <?php if ($canApprove) { ?>
            <form name="WorkflowComments" id="workflow-commentscrud-datatable<?= $model_camelized_name ?>WorkflowComments1-pjax-form">
                <div class="col-md-12 form-label" style="width:100%; padding:15px; position: fixed; bottom: 0px; left:0px; margin-right: auto; margin-left: auto;">
                        <div class="col-md-2">
                            <?= FHtml::showCurrentUserAvatar() ?> &nbsp;
                            <?= FHtml::showUser(FHtml::currentUserId()) ?>
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" name = "WorkflowComments[comment]"></textarea>
                        </div>

                        <div class="col-md-2" style="padding-top:5px">
                                <?php
                                echo FHtml::showJSButton($object_type,[$pjax_container, FHtml::STATUS_APPROVED],  FHtml::t('common', FHtml::STATUS_APPROVED), 'btn-success');
                                echo FHtml::showJSButton($object_type,[$pjax_container, FHtml::STATUS_REJECTED], FHtml::t('common', FHtml::STATUS_REJECTED), 'btn-default');

                                ?>
                        </div>
                </div>
            </form>

        <?php } else { ?>

        <?php } ?>
    </div>
</div>

