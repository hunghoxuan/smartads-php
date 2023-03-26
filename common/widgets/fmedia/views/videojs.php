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

$id = isset($id) ? $id : 'videojs';
$width = isset($width) ? $width : '100%';
$height = isset($height) ? $height : '500';
?>

<?= \wbraganca\videojs\VideoJsWidget::widget([
    'id' => $id,
    'options' => [
        'class' => 'video-js vjs-default-skin vjs-big-play-centered',
        'poster' => "",
        'controls' => true,
        'preload' => 'auto',
        'width' => $width,
        'height' => $height,
        'data' => ['setup' => ['autoplay' => true]],

    ],
    'tags' => [
        'source' => [],
        'track' => [
            ['kind' => 'captions', 'src' => 'http://vjs.zencdn.net/vtt/captions.vtt', 'srclang' => 'en', 'label' => 'English']
        ]
    ]
]); ?>

<ul>
    <?php
    foreach ($items as $item) {
        $src = FHtml::getFieldValue($item, ['file_name', 'file', 'src']);
        $name = FHtml::getFieldValue($item, ['name', 'title']);
        $image = FHtml::getFieldValue($item, ['image', 'thumbnail']);
        $type = FHtml::getFieldValue($item, ['file_type', 'type']);
        echo "
            <li>
                <a href=\"javascript:loadVideojs('$id', '$src','$image', '$type')\">$name</a>
            </li>
            ";
    };
    ?>
</ul>

<script>
    function loadVideojs(myplayer, myFile, myImage, myType) {
        var player = videojs('videojs-' + myplayer, { "controls": true, "autoplay": true, "preload": "auto" });
        player.src([{
            type: myType,
            src: myFile,
            image: myImage
        }]);
        player.play();
    };
</script>


