<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data array
 */
use backend\assets\CustomAsset;
//$asset = \frontend\assets\CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;

?>

<ul class="nav navbar-nav">
    <?php echo $this->render('_menu', array(
            'data' => $data,
        )
    ); ?>
</ul>