<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 12/07/2017
 * Time: 16:23 CH
 */
$url = isset($url) ? $url : 'http-mozagroup-com';
//documents  : http://mozagroup.com/ -> http-mozagroup-com

?>

<script>
    (function() { // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        s.src = 'https://<?= $url ?>.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>