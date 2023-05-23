<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;
use backend\modules\smartscreen\Smartscreen;

if (!is_array($data)) {
    echo $data;
    return;
}
$schedules = [];

foreach ($data as $i => $items) {
    if (is_array($items['schedules']))
        $schedules = array_merge($schedules, $items['schedules']);
}

$preview = FHtml::getRequestParam('preview');

$id = FHtml::getRequestParam('id'); //specific schedule_id
if (strlen($id) > 0 && is_numeric($id)) {
    foreach ($schedules as $schedule) {
        if ($schedule->id == $id) {
            echo \backend\modules\smartscreen\Smartscreen::showLayoutPreview($schedule->data);
            die;
        }
    }
}
?>
<style>
    body {
        margin: 0px !important;
    }

    .slide {
        display: none;
        position: absolute;
        height: 100%;
        width: 100%;
        text-align: center;
        background-color: #cccccc;
    }

    /* Slideshow container */
    .slides {
        width: 100%;
        height: 100%;
        position: absolute;
        margin: auto;
        background-color: #fefefe;
    }

    img {
        vertical-align: middle;
        object-fit: contain;
    }

    video {

        vertical-align: middle;
        object-fit: contain;
    }

    /* Caption text */
    .text1 {
        color: #fff;
        font-weight: lighter;
        font-size: 2vw;
        padding: 15px 12px;

        position: absolute;
        bottom: 0px;
        width: 100%;
        text-align: center;
        background-color: #000;
        opacity: 0.3;

    }

    .text {
        background-color: #000;
        color: white;
        font-size: 2vw;
        font-weight: bold;
        margin: 0 auto;
        padding: 10px;
        width: 100%;
        text-align: center;
        position: absolute;
        bottom: 0%;
        left: 0%;
        /*mix-blend-mode: screen; */
        opacity: 0.3;
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        background-color: black;
        opacity: 0.5;
        bottom: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    .active {
        background-color: #717171;
    }

    /* Fading animation */
    .fade-in {
        -webkit-animation: fadein 2s linear forwards;
        animation: fadein 2s linear forwards;
    }

    .fade-out {
        -webkit-animation: fadeout 2s linear forwards;
        animation: fadeout 2s linear forwards;
    }

    .slide-in {
        -webkit-animation: slidein 2s linear forwards;
        animation: slidein 2s linear forwards;
    }

    .slide-out {
        -webkit-animation: slideout 2s linear forwards;
        animation: slideout 2s linear forwards;
    }

    .slide-left {
        -webkit-animation: slideleft 2s linear forwards;
        animation: slideleft 2s linear forwards;
    }

    .slide-right {
        -webkit-animation: slideright 2s linear forwards;
        animation: slideright 2s linear forwards;
    }

    .grow {
        -webkit-transform: scale(1.3);
        -ms-transform: scale(1.3);
        transform: scale(1.3);
    }

    @-webkit-keyframes fadein {
        from {
            opacity: 0
        }

        to {
            opacity: 1
        }
    }

    @keyframes fadein {
        from {
            opacity: 0
        }

        to {
            opacity: 1
        }
    }

    @-webkit-keyframes fadeout {
        from {
            opacity: 1
        }

        to {
            opacity: 0
        }
    }

    @keyframes fadeout {
        from {
            opacity: 1
        }

        to {
            opacity: 0
        }
    }

    @-webkit-keyframes fadeinout {

        0%,
        100% {
            opacity: 0;
        }

        50% {
            opacity: 1;
        }
    }

    @keyframes fadeinout {

        0%,
        100% {
            opacity: 0;
        }

        50% {
            opacity: 1;
        }
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {
        .text {
            font-size: 11px
        }
    }

    /* Chrome, Safari, Opera */
    @-webkit-keyframes slidein {
        0% {
            background: red;
            left: 0px;
            top: -800px;
        }

        100% {
            background: red;
            left: 0px;
            top: 0px;
        }
    }

    @keyframes slidein {
        0% {
            background: red;
            left: 0px;
            top: -800px;
        }

        100% {
            background: red;
            left: 0px;
            top: 0px;
        }
    }

    @keyframes slideout {
        0% {
            background: red;
            left: 0px;
            top: 0px;
        }

        100% {
            background: red;
            left: 0px;
            top: 800px;
        }
    }

    @-webkit-keyframes slideout {
        0% {
            background: red;
            left: 0px;
            top: 0px;
        }

        100% {
            background: red;
            left: 0px;
            top: 800px;
        }
    }

    @-webkit-keyframes slideleft {
        0% {
            background: red;
            left: -1000px;
            top: 0px;
        }

        100% {
            background: red;
            left: 0px;
            top: 0px;
        }
    }

    @keyframes slideleft {
        0% {
            background: red;
            left: -1000px;
            top: 0px;
        }

        100% {
            background: red;
            left: 0px;
            top: 0px;
        }
    }

    @keyframes slideright {
        0% {
            background: red;
            left: 0px;
            top: 0px;
        }

        100% {
            background: red;
            left: 1000px;
            top: 0px;
        }
    }

    @-webkit-keyframes slideright {
        0% {
            background: red;
            left: 0px;
            top: 0px;
        }

        100% {
            background: red;
            left: 1000px;
            top: 0px;
        }
    }

    .rotate {
        animation: rotate_627320157 2s linear infinite;
        transform-origin: 50% 50%;
    }

    @keyframes rotate_627320157 {
        0% {
            transform: perspective(100px)rotate(0deg)
        }

        12.5% {
            transform: perspective(100px)rotateX(180deg)rotateY(0);
        }

        25% {
            transform: perspective(100px)rotateX(180deg)rotateY(180deg);
        }

        37.5% {
            transform: perspective(100px)rotateX(0)rotateY(180deg);
        }

        50% {
            transform: perspective(100px)rotateX(0)rotateY(0);
        }

        100% {
            transform: perspective(100px)rotateX(0)rotateY(0);
        }
    }
</style>

<?php if ($preview) { ?>
    <div id="preview" class="pull-left" style="width: 20%;background-color: lightgray">
        <?php foreach ($schedules as $schedule) {
            echo $schedule->start_time;
            echo ': ';
            echo $schedule->duration;
            echo '<br/>';
        ?>

        <?php } ?>
    </div>
<?php } else { ?>
<?php } ?>

<div id="slides" class="slides">
    <?php

    $start_time = isset($start_time) ? $start_time : FHtml::getRequestParam('start_time', date('H:i'));

    $default_schedule = isset($default_schedule) ? $default_schedule : Smartscreen::getDefaultSchedule();
    if (!isset($schedules[0])) {
        $schedules[0] = $default_schedule;
    }
    $first_schedule = $schedules[0];
    $duration_before =  Smartscreen::getDurationBetween($start_time, 0, $first_schedule->start_time, true);
    $start_time_outside = false;
    if ($duration_before > 0) {
        $schedule2 = new \backend\modules\smartscreen\models\SmartscreenSchedulesAPI();
        $schedule2->setData($default_schedule->getData());
        $schedule2->id = $default_schedule->id;
        $schedule2->start_time = $start_time;
        $schedule2->duration = $duration_before;
        $schedules = array_merge([$schedule2], $schedules);
        $start_time_outside = true;
    }

    $last_schedule = $schedules[count($schedules) - 1];
    $duration_after = Smartscreen::getDurationBetween($last_schedule->start_time, $last_schedule->duration, '24:00', true);
    if ($duration_after > 0) {
        $schedule1 = new \backend\modules\smartscreen\models\SmartscreenSchedulesAPI();
        $schedule1->setData($default_schedule->getData());
        $schedule1->id = $default_schedule->id;
        $schedule1->start_time = Smartscreen::getNextStartTime($last_schedule->start_time, $last_schedule->duration, 1, null, true);
        $schedule1->duration = $duration_after;
        $schedules = array_merge($schedules, [$schedule1]);
    }

    $first_schedule1 = null;


    foreach ($schedules as $i => $schedule) {
        if (!$start_time_outside) {
            $duration_before = Smartscreen::getDurationBetween($schedule->start_time, 0, $start_time, true);
            $duration_after = Smartscreen::getDurationBetween($schedule->start_time, $schedule->duration, $start_time, true);
            if ($duration_before >= 0 && $duration_after >= 0)
                continue;
            if (!isset($first_schedule1)) {
                $schedule->start_time = $start_time;
                $schedule->duration = $duration_after;
                $first_schedule1 = $schedule;
                $duration = -1 * $duration_after * 60 * 1000;
            } else {
                $duration = $schedule->duration * 60 * 1000;
            }
        } else {
            $duration = $schedule->duration * 60 * 1000;
        }
        $minutes = $duration / (60 * 1000);
        $end = Smartscreen::getNextStartTime($schedule->start_time, $minutes, 1, null, true);
        //$duration = 20000;
    ?>
        <div class="slide" start="<?= $schedule->start_time ?>" end="<?= $end ?>" minutes="<?= $minutes ?>" duration="<?= $duration ?>" id="<?= $schedule->id ?>" style="">
            <div></div>
            <div class="numbertext"> <i class="fas fa-clock"></i><?= $schedule->start_time ?> - <?= $end ?> [<?= ($i + 1) ?>/<?= count($schedules) ?>] @<?= $schedule->id ?> </div>

        </div>

    <?php
    }


    ?>
</div>

<script>
    var slideIndex = 0;
    var slides = document.getElementsByClassName("slide");
    var slidesContent = [];

    showSlides();
    window.addEventListener('resize', resize, false);
    resize();

    function resizeObject(video) {
        //console.log(video);
        videoRatio = video.height / video.width;
        windowRatio = window.innerHeight / window.innerWidth; /* browser size */

        if (windowRatio < videoRatio) {
            if (window.innerHeight > 50) {
                /* smallest video height */
                video.height = window.innerHeight;
            } else {
                video.height = 50;
            }
        } else {
            video.width = window.innerWidth;
        }
    }

    function resize() {
        return;
        var videos = document.getElementsByTagName("video");
        for (i = 0; i < videos.length; i++) {
            resizeObject(videos[i]);
        }

        var images = document.getElementsByTagName("img");
        for (i = 0; i < images.length; i++) {
            resizeObject(images[i]);
        }
    };

    function getControlFromSlide(slide) {
        return slide.children[0];
    }

    function showSlides() {
        var i;
        var slide;
        var type;
        var transition_out;
        var transition_in;
        var transition;
        var duration;
        var id;

        for (i = 0; i < slides.length; i++) {
            slide = slides[i];
            //console.log(slide);
            type = slide.getAttribute("type");
            transition_in = slide.getAttribute("transition-in");
            transition = slide.getAttribute("transition");

            if (transition_in == '' || transition_in == null)
                transition_in = transition == null ? 'fade-in' : transition;
            slide.setAttribute('class', 'slide ' + transition_in);

            slide.style.display = "none";
        }
        slide = slides[slideIndex];
        duration = slide.getAttribute("duration");
        transition_out = slide.getAttribute("transition-out");
        transition_in = slide.getAttribute("transition-in");
        transition = slide.getAttribute("transition");
        id = slide.getAttribute("id");

        if (id != null && id != '') {
            console.log(window.location + "&id=" + id);
            slide.children[0].innerHTML = "<iframe src='" + window.location + "&id=" + id + "' width='100%' height='100%' />";
        }

        if (transition_in == '' || transition_in == null)
            transition_in = transition == null ? 'fade-in' : transition;

        if (transition_out == '' || transition_out == null)
            transition_out = transition == null ? 'fade-out' : transition;

        type = slide.getAttribute("type");

        slide.style.display = "block";

        slideIndex++;
        if (slideIndex > slides.length - 1) {
            slideIndex = 0
        }

        if (duration > 0) {
            //console.log($(slide));
            setTimeout(() => {
                slide.setAttribute('class', 'slide ' + transition_out);

                setTimeout(() => {
                    slide.children[0].innerHTML = '';
                    showSlides();
                }, 2000);
            }, duration);
        }


    }
</script>