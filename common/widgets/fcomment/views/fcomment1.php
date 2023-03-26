<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/6/2016
 * Time: 4:43 PM
 */
use frontend\assets\CustomAsset;
Use frontend\components\Helper;
use common\components\FHtml;
use yii\widgets\Pjax;

/* @var $product \backend\modules\ecommerce\models\Products */
/* @var $this yii\web\View */
/* @var $products array */
/* @var $page integer */
/* @var $category_id integer */
/* @var $title string */
/* @var $keyword string */
/* @var $total int */
/* @var $start_index int */

$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';

$user_id = !isset($user_id) ? FHtml::currentUserId() : $user_id;
$object_id = isset($object_id) ? $object_id : FHtml::getRequestParam(['id', 'object_id']);
$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam(['object_type']);
$id = $object_id;

$comments = null;
if (!empty($id))
    $comments = isset($comments) ? $comments : FHtml::getModels('object_comment', ['object_id' => $id, 'object_type' => $object_type], 'created_date asc');
?>
<?php Pjax::begin(['id' => 'pjax-container12']) ?>

<div class="row">
    <?php if (!empty($comments)) {
        $count = count($comments);

        echo "<div style='padding-top:20px; padding-bottom:20px; color: #81C6B6; font-size: 24pt'>{$count} Comments. </div>";

        foreach ($comments as $model) {
            if ($model->user_type == 'user' ) {
                $is_from = true;
                $style = 'background-color: white;';
            }
            else {
                $is_from = false;
                $style = 'background-color: #ecc';
            }
            ?>
            <div class="col-md-12" style="border-bottom: 1px dashed lightgrey; padding-bottom:20px;margin-bottom:20px">
                <div class="col-md-1">
                    <?= FHtml::showImage($model->getAppUser()->avatar, 'app-user', '50px', '50px', 'img-circle') ?>
                </div>
                <div class="col-md-11">
                    <b style="color: #f08f66"> <?= explode('@', $model->getAppUser()->username)[0] ?> </b>
                    <div class="pull-right" style="font-size:80%;color:grey"> <?= FHtml::showDate($model->created_date) ?> </div>
                    <div style="padding-top:10px">
                        <?= $model->comment ?>
                    </div>
                </div>

            </div>
        <?php }
    } else { ?>
        <div style="padding:20px; color: grey; font-size: 24pt"> No comment. </div>
    <?php } ?>
    <?php if (!empty($user_id)) { ?>
    <div class="col-md-12" style="">
        <div class="col-md-1" style="">
            <?= FHtml::showImage(FHtml::getFieldValue(FHtml::currentUserIdentity(), ['avatar', 'image']), 'app-user', '50px', '50px', 'img-circle') ?>
        </div>
        <div class="col-md-11" style="">
            <?php
            FHtml::registerPlusJS('object_comment', ['app_user_id', 'user_id', 'object_id', 'object_type', 'comment', 'created_date'], 'pjax-container12', '{column}');
            ?>
            <input type="hidden" name="app_user_id" id="app_user_id" value="<?= $user_id ?>" />
            <input type="hidden" name="user_type" id="user_type" value="app_user" />
            <input type="hidden" name="created_date" id="created_date" value="<?= date('Y-m-d') ?>" />
            <input type="hidden" name="object_id" id="object_id" value="<?= $object_id ?>" />
            <input type="hidden" name="object_type" id="object_type" value="<?= $object_type ?>" />
            <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Reply"></textarea>
            <button style="margin-top:10px" href="#" class="btn btn-success btn-md" onclick="plusObjectComment()" ><?= FHtml::t('common', 'Send') ?></button>
        </div>
    </div>
    <?php } ?>
</div>

<?php Pjax::end() ?>

