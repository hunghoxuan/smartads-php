<?php
use common\components\FHtml;
use yii\widgets\Pjax;

?>

<?php
$type = '';
$field_name = empty($field_name) ? 'name' : $field_name;
$field_key = empty($field_key) ? 'id' : $field_key;
$widget_id = empty($id) ? uniqid() : $id;
if (!isset($canEdit))
    $canEdit = FHtml::isRoleAdmin();
?>

<script>
    function updateSortOrder <?=$widget_id ?>($object_type, $sort_orders) {
        $.ajax({
            url: '<?= FHtml::createUrl('/api/sort-order', []) ?>?object_type=' + $object_type + '&sort_orders=' + $sort_orders,
            type: 'get',
            data: {object_type: $object_type, sort_orders: $sort_orders},
            success: function (data) {
                $.pjax.reload('#pjax-container-<?= $widget_id ?>', {timeout: false})
            }
        });
    }
</script>

<?php if ($canEdit) { ?>
    <?php Pjax::begin(['id' => 'pjax-container-' . $widget_id]) ?>

    <div class="" id="<?= $widget_id ?>">
        <div class="col-md-12">
            <div class="col-md-12">
                <?php
                $controls = [];
                $i = 0;
                foreach ($items as $item) {
                    $i = $i + 1;
                    $controls[] = ['content' => '<div class="" style="" id="' . $item[$field_key] . '"  name="' . $item[$field_key] . '" >' . $i . '. ' . $item[$field_name] . '</div>'];
                }
                echo \kartik\sortable\Sortable::widget([
                    'showHandle' => false,
                    'id' => $widget_id . '_sortorder',
                    'items' => $controls,
                    'pluginEvents' => [
                        'sortstart' => 'function() { }',
                        'sortupdate' => 'function() { 
                        $result = [];
                        $("#' . $widget_id . '_sortorder > li").children().each(function( index, element ) { $result.push($(this).attr("id")); });
                        updateSortOrder' . $widget_id . '("' . $object_type . '", $result);
                }',
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php Pjax::end() ?>

<?php } else { ?>

<?php } ?>


