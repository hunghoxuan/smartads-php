<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 12/07/2017
 * Time: 21:38 CH
 */
use common\components\FHtml;
$appID =  isset($appID) ? $appID : '1619062661461455';
if (FHtml::currentLang() == 'en') $lang = 'en_Us';
else $lang = 'vi_VN';
?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/<?= $lang ?>/sdk.js#xfbml=1&version=v2.9&appId=<?= $appID ?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>