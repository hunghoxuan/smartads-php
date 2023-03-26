<?php
$google_adwords_key = isset($google_adwords_key) ? $google_adwords_key : \common\components\FHtml::settingWebsite('google_adwords_key');
?>

<!-- Global site tag (gtag.js) - Google AdWords: 805818623 -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-<?=$google_adwords_key ?>\"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'AW-<?=$google_adwords_key ?>');
</script>
<!-- Event snippet for Source code selling conversion page -->
<script>
    gtag('event', 'conversion', {
        'send_to': 'AW-<?=$google_adwords_key ?>/sm1iCLrp-YEBEP-hn4AD',
        'transaction_id': ''
    });
</script>";