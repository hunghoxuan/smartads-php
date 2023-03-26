<?php
$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingWebsite('chat_url');
?>

<?php $this->registerJs('(function(d,s,id){var z=d.createElement(s);z.type="text/javascript";z.id=id;z.async=true;z.src="' . $chat_url .'";var sz=d.getElementsByTagName(s)[0];sz.parentNode.insertBefore(z,sz)}(document,"script","zb-embed-code"));'); ?>
