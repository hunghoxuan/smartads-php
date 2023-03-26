<?php
$baseUrl = \common\components\FHtml::getBaseUrl($this);
$link_url = !isset($link_url) ? '' : $link_url;
$title = !isset($title) ? \common\components\FHtml::config(\common\components\FHtml::SETTINGS_COMPANY_NAME) : $title;

$size = !isset($size) ? 'md' : $size;
$shape = !isset($shape) ? '' : 'ssk-' . $shape;

$float = (!isset($float) || $float == false || $float == 'none') ? '' : $float;
if (!empty($float))
    $float = 'ssk-sticky ssk-center ssk-' . $float;

$show_icon = !isset($show_icon) ? '' : 'ssk-icon';
$show_count = !isset($show_count) ? '' : $show_count;

if ($show_count === true)
    $show_count = 'ssk-count';

if (!is_array($items)) {
    $items = empty($items) ? 'facebook,twitter' : $items;
    $items = explode(',', $items);
}
?>

<link rel="stylesheet" type="text/css"
      href="<?php echo $baseUrl ?>/frontend/themes/common/assets/plugins/social-share/css/social-share-kit.css">

<div
    class="ssk-group data-text='<?= $title ?>' data-url='<?= $link_url ?>' <?= $shape ?> <?= $float ?> <?= $show_icon ?> <?= $show_count ?> ssk-<?= $size ?>"
    style="margin-left:5px">
    <?php foreach ($items as $item) {
        echo '<a href="' . $link_url . '" class="ssk ssk-' . $item . '"></a>';
    }
    ?>
</div>
<?php $this->registerJsFile($baseUrl . "/frontend/themes/common/assets/plugins/social-share/js/social-share-kit.js?v=1.0.12", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs("
      SocialShareKit.init({
        url: '" . $link_url . "',
        twitter: {
            title: '" . '' . "',
            via: ''
        },
        onBeforeOpen: function(targetElement, network, paramsObj){
            console.log(arguments);
        },
        onOpen: function(targetElement, network, url, popupWindow){
            console.log(arguments);
        },
        onClose: function(targetElement, network, url, popupWindow){
            console.log(arguments);
        }
    });

    $(function () {
        // Just to disable href for other example icons
        $('.ssk').on('click', function (e) {
            e.preventDefault();
        });

        // Navigation collapse on click
        $('.navbar-collapse ul li a:not(.dropdown-toggle)').bind('click', function () {
            $('.navbar-toggle:visible').click();
        });

        // Email protection
        $('.author-email').each(function () {
            var a = '@', em = 'support' + a + 'social' + 'sharekit' + '.com', t = $(this);
            t.attr('href', 'mai' + 'lt' + 'o' + ':' + em);
            !t.text() && t.text(em);
        });

        // Sticky icons changer
        $('.sticky-changer').click(function (e) {
            e.preventDefault();
            var \$link = $(this);
            $('.ssk-sticky').removeClass(\$link.parent().children().map(function () {
                return $(this).data('cls');
            }).get().join(' ')).addClass(\$link.data('cls'));
            \$link.parent().find('.active').removeClass('active');
            \$link.addClass('active').blur();
        });
    });
 "); ?>

<script type="text/javascript">
    // This code is required if you want to use Twitter callbacks
    window.twttr = (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function (f) {
            t._e.push(f);
        };

        return t;
    }(document, "script", "twitter-wjs"));

    // Demo callback
    function twitterDemoCallback(e) {
        $('#twitterEvents').append(e.type + ' ');
    }

    // Bind to Twitter events
    twttr.ready(function (tw) {
        tw.events.bind('click', twitterDemoCallback);
        tw.events.bind('tweet', twitterDemoCallback);
    });

</script>