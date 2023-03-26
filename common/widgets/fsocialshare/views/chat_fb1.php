<?php
$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingCompanyFacebook(false);
$email = isset($email) ? $email : \common\components\FHtml::settingCompanyEmail();
?>

<script type='text/javascript'>
    (function() { var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//d2yy16lkdmfg04.cloudfront.net/resource/facebookchat.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })(); window.embeddedChatFacebookAsyncInit = function() { embeddedChatFacebook.init("9526"); }
</script>
<!--Facebook Chat Widget - Made by Supple Solutions - https://supple.com.au/tools/facebook-messenger-website-chat-widget/ -->