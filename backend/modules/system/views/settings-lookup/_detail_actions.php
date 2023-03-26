<?php
use common\components\FHtml;
?>

<script language="javascript" type="text/javascript">
    function submitForm($saveType) {
        $('#saveType').val($saveType);
    }
</script>

<?php if (Yii::$app->request->isAjax) { ?>

    <input type="hidden" id="saveType" name="saveType">

<?php } else { ?>
    <input type="hidden" id="saveType" name="saveType">

    <div class="">
        <?php if ($canEdit) { echo         FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
        echo '  ' . FHtml::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save') . ' & ' . FHtml::t('common', 'Clone'), ['class' => 'btn btn-warning', 'onclick' => 'submitForm("clone")']); } ?>
        <?php  if (!$model->isNewRecord && $canDelete) {?>
        <?=  FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger pull-right',
        'data' => [
        'confirm' => FHtml::t('common', 'Are you sure to delete?'),
        'method' => 'post',
        ],
        ]); ?>
        <?php }  ?>
        <?=  ' | ' . FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>
<?php } ?>