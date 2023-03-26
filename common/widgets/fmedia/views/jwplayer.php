<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data array
 */
use yii\helpers\Html;
use backend\assets\CustomAsset;
use common\components\FHtml;

$id = isset($id) ? $id : 'jwplayer';
$items = isset($items) ? $items : [];
?>

<?= \wadeshuler\jwplayer\JWPlayer::widget([
    'id' => $id,
    'playerOptions' => [
        'autostart' => true,
        'playlist' => $items,
        'file' => '/stech-vss/backend/web/upload/videos/cam1_002527.mp4'
    ]
]) ?>

<ul style="playlist">
    <?php
    foreach ($items as $item) {
        $src = FHtml::getFieldValue($item, ['full_path', 'file_name', 'file', 'src']);
        $name = FHtml::getFieldValue($item, ['name', 'title']);
        $image = FHtml::getFieldValue($item, ['image', 'thumbnail']);
        $type = FHtml::getFieldValue($item, ['file_type', 'type']);
        echo "
            <li>
                <a href=\"javascript:loadJwPlayer('$id', '$src','$image', '$type')\">$name</a>
            </li>
            ";
    };
    ?>
</ul>

<script>
    function loadJwPlayer(myplayer, myFile, myImage, myType) {
        var playerInstance = jwplayer(myplayer);
        playerInstance.load([{
            file: myFile,
            image: myImage,
            type: myType
        }]);

        playerInstance.play();
    };
</script>

<style>
    ul {
        list-style: none; /* Remove list bullets */
        padding: 0;
        margin: 0;
    }

    ul li {
        padding-left: 16px;
    }
</style>

