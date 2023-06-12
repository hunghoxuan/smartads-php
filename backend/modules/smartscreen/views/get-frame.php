<?php
$frame = isset($frame) ? $frame : null;
if (!function_exists('_getHtmlContent')) {
    function _getHtmlContent($content)
    {
        return '<p style="text-align:center;margin: 0;position: relative; font-size:50%;top: 50%;transform: translateY(-50%);">' . $content . '</p>';
    }
}
if (is_object($frame)) {
    $width = $frame->percentWidth;
    $height = $frame->percentHeight;
    $marginLeft = $frame->marginLeft;
    $marginTop = $frame->marginTop;
    $backgroundColor = isset($frame->backgroundColor) ? $frame->backgroundColor : 'transparent';
    $classSelect = isset($classSelect) ? $classSelect : '';
    $number = intval(preg_replace('/[^0-9]+/', '', $classSelect), 10);
    $url = isset($url) ? $url : '';
    $content = isset($content) ? $content : '';
    if (empty($content)) {
        if (!empty($url))
            $content = "<iframe frameBorder='0' src='$url' width='100%' height='100%'></iframe>";
        else
            $content = _getHtmlContent($frame->name);
    }
    $id = $frame->id;
} else if (is_array($frame)) {
    if (isset($frame[0]) && !isset($frame['percentWidth']))
        $frame = $frame[0];

    $width = $frame['percentWidth'];
    $height = $frame['percentHeight'];
    $marginLeft = $frame['marginLeft'];
    $marginTop = $frame['marginTop'];
    $backgroundColor = isset($frame['backgroundColor']) ? $frame['backgroundColor'] : 'transparent';
    $classSelect = isset($classSelect) ? $classSelect : '';
    $number = intval(preg_replace('/[^0-9]+/', '', $classSelect), 10);
    $url = isset($url) ? $url : '';
    $content = isset($content) ? $content : '';
    if (isset($frame['data']) && isset($frame['data'][0]) && $frame['data'][0]['dataType'] == 'html') {
        $url = $frame['data'][0]['url'];
        $content = $frame['data'][0]['description'];
    } else if ($frame['contentLayout'] == 'html') {
        $content = $frame['data'][0]['description'];
    }
    if (empty($content)) {
        if (!empty($url))
            $content = "<iframe frameBorder='0' src='$url' width='100%' height='100%'></iframe>";
        else if (!empty($frame['data']) && is_array($frame['data'])) {
            $models = new \backend\modules\smartscreen\models\SmartscreenContent();
            //var_dump($frame); die;
            $models->type = $frame['contentLayout'];
            $models->list_content = [];
            foreach ($frame['data'] as $frameItem) {
                if (!isset($frameItem['url'])) {
                    continue;
                }
                $item = new \backend\modules\smartscreen\models\SmartscreenFileAPI();
                $item->file_duration = $frameItem['duration'];
                $item->file_kind = $frameItem['kind'];
                $item->file = $frameItem['url'];
                $item->description = $frameItem['title'];
                $item->command = $frameItem['dataType'];
                $models->list_content[] = $item;
            }
            $content = \common\components\FHtml::renderView('content/index', ['models' => $models]);
        } else {
            $content = _getHtmlContent($frame['name']);
        }
    }
    $id = $frame['id'];
} else {
    $content = _getHtmlContent($content);
}

?>
<div class="frame-for-layout frame_<?= $id ?> <?= $classSelect ?>" style="
    background-color: <?= $backgroundColor ?>;
    color: <?= $backgroundColor == '#000000' ? '#fff' : '#000' ?>;
    text-align: center;
    font-size: 150%;
    width: <?= $width ?>%;
    height: <?= $height ?>%;
    left: <?= $marginLeft ?>%;
    top: <?= $marginTop ?>%;
    position: absolute;
    z-index: <?= $number ?>;
    border: 1px solid darkgrey;">
    <?= $content ?>
</div>