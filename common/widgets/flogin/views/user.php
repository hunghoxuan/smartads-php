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
use frontend\components\FEcommerce;
use backend\assets\CustomAsset;


/* @var $model \common\models\LoginFormFrontend */
/* @var $model2 \frontend\models\SignupForm */


//$asset = \frontend\assets\CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = FHtml::currentUserIdentity();
$appuser = \Yii::$app->appuser->identity;
$user1 = \Yii::$app->user->identity;

if (FHtml::isRoleAdmin())
    $url = FHtml::createUrl('/', [], FRONTEND);
else
    $url = FHtml::createUrl('user/inbox');
?>


<?php if (!isset($user)) { ?>
    <ul class="navbar-nav navbar-user">
        <li>
            <a class="cd-signin" href="javascript:void(0);"><b>Login</b></a>
        </li>
        <li>
            <a class="cd-signup" href="javascript:void(0);">Register</a>
        </li>
    </ul>
<?php } else { ?>
    <ul class="navbar-nav navbar-user">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <b><?= explode('@', $user->username)[0] ?></b>
            </a>
            <ul class="dropdown-menu">

                <?php
                if (FHtml::config('Is User Messages Enabled', true, [], 'Website', FHtml::EDITOR_BOOLEAN))
                { ?>
                    <li><a href="<?= $url  ?>">
                        <i class="glyphicon glyphicon-envelope"></i>&nbsp; <?= FHtml::t('common', 'Dashboard') ?>
                    </a>                    </li><?php } ?>
                <li><a href="#" onclick="logout()"> <i class="glyphicon glyphicon-log-out"></i> <?= FHtml::t('common', 'Log out') ?> </a></li>
            </ul>
        </li>
    </ul>

<?php } ?>


<script>
    function logout() {
        $.ajax({
            url: '<?= FHtml::createUrl('logout', []) ?>',
            type: 'post',
            success: function (data) {
                if (data == 1)
                    reloadPage();
                else
                    alert('Could not logout');
            }
        });
    }

    function reloadPage() {
        location.reload();
    }
</script>
<style>
    ul.navbar-user > li {
        border-bottom: none !important;
        list-style-type: none !important;
        margin-bottom: 5px;
    }

    ul.navbar-user > li > a {
        border-bottom: none !important;
        font-size: 16px !important;
        padding-left: 0px !important;
    }
    ul.navbar-user > li:hover > a {
        color: #9EF6EC !important;
        border-bottom: 3px solid #72c02c;
    }
</style>
