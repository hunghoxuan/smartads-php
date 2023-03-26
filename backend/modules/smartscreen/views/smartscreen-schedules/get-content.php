<?php
use backend\modules\smartscreen\controllers\SmartscreenSchedulesController;
use backend\modules\smartscreen\models\SmartscreenContent;
use yii\helpers\ArrayHelper;

$list_content = SmartscreenContent::find()->orderBy('type asc, title asc')->all();


$list_frame = $layout->frameQuery;
$count = count($list_frame);
$i = 0;
$render = '';
$content_id = null;
$selected = empty($content_json) ? 'selected' : '';

$number = intval(preg_replace('/[^0-9]+/', '', $selectId), 10);
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();
?>

<?php
//render demo html
foreach ($list_frame as $frame) {
    $backgroundColor = \backend\modules\smartscreen\controllers\SmartscreenLayoutsController::random_color();
    $render .= $this->render('@backend/modules/smartscreen/views/get-frame', ['frame' => $frame, 'backgroundColor' => $backgroundColor]);
}
?>

<?php foreach ($list_frame as $frame): ?>
    <tr class="child_<?= $selectId ?>  form-label" style="background-color: ; border:1px dashed lightgrey;">

        <?php if ($i == 0) { ?>
            <td rowspan="<?= $count + 1 ?>" colspan="1" class="form-label"
                style="background-color: #000000; position: relative; padding: 0">

                <div class="demo_layout_schedule" style="width: 100%; height: 100%;">
                    <?= $render ?>
                </div>
            </td>
        <?php } ?>
        <td class="col-md-2 form-label" style="padding-left:10px">
            <a href="javascript:void(0)" class="data-link" data-link="<?= $baseUrl ?>/index.php/smartscreen/smartscreen-frame/update?id=<?= $frame->id ?>"><?= $frame->name ?></a>
        </td>
        <td colspan="3">
            <?php echo SmartscreenSchedulesController::listContent($frame, $number, $selected, $list_content, $content_json, $i) ?>
        </td>
        <td class="view_content">
            <?php
            if (isset($content_json['content_id'][$i])) {
                $content_id = $content_json['content_id'][$i];
            }
            echo SmartscreenSchedulesController::updateContent($content_id);
            ?>
        </td>
    </tr>
    <?php $i++ ?>
<?php endforeach ?>
<tr class="child_<?= $selectId ?>  form-label" style="background-color: ; border:1px dashed lightgrey;">
    <td style="padding-left:10px;" class="form-label"><?= \common\components\FHtml::t('', 'Background Audio') ?></td>
    <td colspan="3">
        <?php echo SmartscreenSchedulesController::listContent(null, $number, $selected, $list_content, $content_json, $i) ?>
    </td>
    <td class="view_content">
        <?php
        if (isset($content_json['content_id'][$i])) {
            $content_id = $content_json['content_id'][$i];
        }
        echo SmartscreenSchedulesController::updateContent($content_id);
        ?>
    </td>
</tr>
<hr/>
<style>
    .view_content {
        text-align: center;
    }
</style>




