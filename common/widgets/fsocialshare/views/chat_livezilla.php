<?php
$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingWebsite(['livezilla_chat_url'], '');
$chat_id = isset($chat_id) ? $chat_id : \common\components\FHtml::settingWebsite(['livezilla_chat_id'], 'lzdefsc');
$enabled = isset($enabled) ? $enabled : \common\components\FHtml::setting('livezilla_chat_enabled', true);
if (!$enabled)
    return;
$chat_url = !empty($chat_url) ? $chat_url : (!empty($chat_id) ? \common\components\FHtml::getRootUrl() . "/apps/livezilla/script.php?id=$chat_id" : '');

?>

<?php if (!empty($chat_url) && $chat_url != false) { ?>
    <!-- livezilla.net PLACE SOMEWHERE IN BODY -->
    <script type="text/javascript" id="<?=$chat_id?>" src="<?= $chat_url ?>" defer></script>
    <!-- livezilla.net PLACE SOMEWHERE IN BODY -->
<?php } ?>

