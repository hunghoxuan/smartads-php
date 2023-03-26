<?php
$chat_url = isset($chat_url) ? $chat_url : \common\components\FHtml::settingCompanyFacebook(false);
$email = isset($email) ? $email : \common\components\FHtml::settingCompanyEmail();
$fb_app_id = isset($fb_app_id) ? $fb_app_id : \common\components\FHtml::setting(['facebook_page_id', 'chat_url'], '339375893090630');
$enabled = isset($enabled) ? $enabled : \common\components\FHtml::setting('facebook_chat_enabled', true);
if (!$enabled)
    return;
?>

<div class="fb-customerchat"
     page_id="<?= $fb_app_id ?>"
     minimized="true">
</div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '<?= $fb_app_id ?>',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.11'
        });
    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div class="fb-messengermessageus"
     messenger_app_id="<?= $fb_app_id ?>"
     page_id="XYZ"
     color="blue"
     size="large">
</div>