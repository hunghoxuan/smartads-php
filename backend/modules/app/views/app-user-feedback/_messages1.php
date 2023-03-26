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

$user_id = !isset($user_id) ? FHtml::currentUserId() : $user_id;
$models = !isset($models) ? FHtml::getModels('app_user_feedback', ['user_id' => $user_id], 'created_date desc') : $models;
$id = isset($id) ? $id : FHtml::getRequestParam('id');
$feedback = isset($feedback) ? $feedback : (empty($id) ? null : FHtml::getModel('app_user_feedback', '', $id, null, false));
$comments = null;
if (!empty($id))
    $comments = isset($comments) ? $comments : FHtml::getModels('object_comment', ['object_id' => $id, 'object_type' => 'app_user_feedback'], 'created_date asc');

$app_user = $model->getUser();

?>
<?php Pjax::begin(['id' => 'pjax-container']) ?>

<div class="col-md-12">
    <div class="col-md-3">
        <div class="text-center" style="margin-bottom:20px; padding:20px; padding-top: 50px;background-color:white; width:100%;border:solid 1px lightgrey; float:left !important">
            <?= FHtml::showImage($app_user->avatar, 'user', '70%') ?> <br/>

            <div style="color: cadetblue; font-size:24px; font-weight: bold; margin:30px"><?= $app_user->username ?></div>
        </div>

        <a class="btn btn-sm btn-default" href="<?= FHtml::createUrl('app/app-user-feedback') ?>"><?= FHtml::t('Back to Inbox') ?> </a>
    </div>
    <div class="col-md-9" style="padding-left:30px">
        <?php if (isset($model))
        { ?>
            <div class="col-md-12">
                <div class="col-md-1 text-center" style="padding:15px; color:grey; font-size:80%">
                    <?= FHtml::showCurrentLogo('', '') ?><br/>
                    <?= FHtml::showDate($model->created_date) ?>
                </div>
                <div class="col-md-10" style="border-radius: 4px; border: 1px solid lightgrey; margin-top: 10px; background-color: white; padding: 20px">
                    <?= $model->comment ?>
                    <br/>
                    <div style="margin-top: 10px; color: grey;">
                        <?= $model->response ?>
                    </div>
                </div>
                <div class="col-md-1 text-center" style="padding:15px">
                </div>
            </div>
            <?php
        } ?><br/>
        <?php if (!empty($comments)) {
            foreach ($comments as $model) {
                if ($model->user_type == 'user' ) {
                    $is_from = true;
                    $style = 'background-color: white;';
                }
                else {
                    $is_from = false;
                    $style = 'background-color: #efefef';
                }
                ?>
                <div class="col-md-12">
                    <div class="col-md-1 text-center" style="padding:15px; color:grey">
                        <?php if ($is_from === true) { ?>
                            <?= FHtml::showCurrentLogo('70%', '', 'img-circle') ?><br/>
                            <?= FHtml::showDate($model->created_date) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-10" style="border-radius: 4px; border: 1px solid lightgrey; margin-top: 15px; padding: 15px; <?= $style ?>">
                        <?= $model->comment ?>
                    </div>
                    <div class="col-md-1 text-center" style="padding:15px; color:grey;">
                        <?php if ($is_from === false) { ?>
                            <?= FHtml::showImage($app_user->avatar, 'user', '70%', 'img-circle') ?><br/>
                            <?= FHtml::showDate($model->created_date) ?>

                        <?php } ?>
                    </div>
                </div>

            <?php }
        } else { ?>
        <?php } ?>
        <hr />
        <div class="col-md-12" style="margin-top: 20px; border-top:1px dashed lightgrey; padding-top:30px">
            <div class="col-md-1 text-center" style="padding:15px">
                <?= FHtml::showCurrentLogo() ?>
            </div>
            <div class="col-md-10">
                <?php
                FHtml::registerPlusJS('object_comment', ['app_user_id', 'user_type', 'object_id', 'object_type', 'comment', 'created_date'], 'pjax-container', '{column}');
                ?>
                <input type="hidden" name="user_id" id="user_id" value="<?= $user_id ?>" />
                <input type="hidden" name="user_type" id="user_type" value="user" />
                <input type="hidden" name="created_date" id="created_date" value="<?= date('Y-m-d') ?>" />
                <input type="hidden" name="object_id" id="object_id" value="<?= $feedback->id ?>" />
                <input type="hidden" name="object_type" id="object_type" value="app_user_feedback" />
                <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Reply"></textarea> <br/>
                <button href="#" class="btn btn-success btn-md" onclick="plusObjectComment()" ><?= FHtml::t('common', 'Send') ?></button>

            </div>
            <div class="col-md-1 text-center" style="padding:15px">
            </div>
        </div>

    </div>
</div>

<?php Pjax::end() ?>

