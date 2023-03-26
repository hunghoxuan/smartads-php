<?php
$google_analytic_key = isset($google_analytic_key) ? $google_analytic_key : \common\components\FHtml::settingWebsite('google_analytic_key');
?>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', '<?= $google_analytic_key ?>', 'auto');
    ga('send', 'pageview');

</script>