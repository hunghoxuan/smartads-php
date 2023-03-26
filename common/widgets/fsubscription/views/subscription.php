<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data array
 */
use common\components\FHtml;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $model \common\models\LoginFormFrontend */
/* @var $model2 \frontend\models\SignupForm */


$asset = \frontend\assets\CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$user = FHtml::currentUserIdentity();

?>
<?php Pjax::begin(['id' => 'pjax-container12']) ?>

<div id="message" style="background-color: #81C6B6; padding: 20px; color: white">
    <h3 style="color:white">Subscribe to us</h3>
    <p style="color:white">
        Signup for the tips and guides that will save you time, money, and have you travelling sooner !
    </p>
    <input class="form-control" name="email" id="email" rows="2" placeholder="Your email" />
    <?php
    FHtml::registerPlusJS('app_user_subscription', ['email', 'category', 'created_date', 'modified_date'], 'pjax-container12', '{column}', [], [], '$("#message").html("<div style=\'font-size:150%\'>' . FHtml::t('common', 'Thank you for submitting. We will be in touch soon !') . '</div>");');
    ?>
    <input type="hidden" name="category" id="category" value="<?= FHtml::currentDomain() ?>" />
    <input type="hidden" name="modified_date" id="modified_date" value="<?= date('Y-m-d') ?>" />
    <input type="hidden" name="created_date" id="created_date" value="<?= date('Y-m-d') ?>" />
    <button style="margin-top:10px" href="#" class="btn btn-success btn-md" onclick="plusAppUserSubscription()" ><?= FHtml::t('common', 'Subscribe') ?></button>
    <div style="color:white; font-size:150%;margin-top:20px"> </div>
</div>

<?php Pjax::end() ?>
