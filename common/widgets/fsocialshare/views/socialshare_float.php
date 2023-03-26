<?php
$baseUrl = \common\components\FHtml::getBaseUrl($this);
$link_url = !isset($link_url) ? 'window.location.href' : "'$link_url'";

$title = isset($title) ? "'$title'" : 'document.title';
$description = isset($description) ? "'$description'" : '$(\'meta[name="description"]\').attr("content")';
$image = isset($image) ? "'$image'" : '$(\'meta[property="og:image"]\').attr("content")';

$position = isset($position) ? $position : 'content-left';

if (is_array($items)) {
    // do nothing
} else {
    $items = empty($items) ? 'facebook,twitter' : $items;
    $items = explode(',', $items);
}
$items_array = [];
foreach ($items as $item)
    $items_array[] = "'$item'";
$items_array = implode(',', $items_array);
$container = !isset($container) ? 'body' : $container;
?>

<?php $this->registerJsFile($baseUrl . "/themes/common/assets/plugins/jquery.floating-social-share/jquery.floating-social-share.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerCssFile($baseUrl . "/themes/common/assets/plugins/jquery.floating-social-share/jquery.floating-social-share.css", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs("$('$container').floatingSocialShare({
    buttons: [
      $items_array
    ],
    text: {'default': 'share with: ', 'facebook': 'share with facebook', 'google-plus': 'share with g+'},
    text_title_case: true,
    place: '$position',
    url: $link_url,
    title: $title,
    description: $description,
    media: $image,
    twitter_counter: true,
    title: $title

  });
 "); ?>

