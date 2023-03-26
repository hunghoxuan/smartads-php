<?php
$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingWebsite(['tidio_chat_url', 'chat_url'], 'https://code.tidio.co/3zqbdsmxw4qy5tpiss8l6dbjjvfvughj.js');
$enabled = isset($enabled) ? $enabled : \common\components\FHtml::setting('tidio_chat_enabled', true);
if (!$enabled)
    return;
?>

<?php if (!empty($chat_url) && $chat_url != false) { ?>
    <script src="<?= $chat_url ?>"></script>
<?php } ?>