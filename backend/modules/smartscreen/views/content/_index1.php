<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php

$slides = $models->list_content;
if ($models->getTableName() == \backend\modules\smartscreen\models\SmartscreenContent::tableName()
    && (!is_array($slides) ||  (is_array($slides) && count($slides) == 0) || !in_array($models->type, ['image', 'video', 'slide']))) {
    echo $models->getContent(); die;
} else {
?>

    <style>
        body { margin: 0px !important;}
        .slide {
            display: none;
            position:absolute;
            height:100%;
            width:100%;
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
            right: 0%;
            /*mix-blend-mode: screen; */
            opacity: 0.3;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            bottom: 0;
            right: 0;
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
            -webkit-animation: fadein 1s linear forwards;
            animation: fadein 1s linear forwards;
        }

        .fade-out {
            -webkit-animation: fadeout 1s linear forwards;
            animation: fadeout 1s linear forwards;
        }

        .slide-in {
            -webkit-animation: slidein 1s linear forwards;
            animation: slidein 1s linear forwards;
        }

        .slide-out {
            -webkit-animation: slideout 1s linear forwards;
            animation: slideout 1s linear forwards;
        }

        .slide-left {
            -webkit-animation: slideleft 1s linear forwards;
            animation: slideleft 1s linear forwards;
        }

        .slide-right {
            -webkit-animation: slideright 1s linear forwards;
            animation: slideright 1s linear forwards;
        }

        .grow
        {
            -webkit-transform: scale(1.3);
            -ms-transform: scale(1.3);
            transform: scale(1.3);
        }

        @-webkit-keyframes fadein {
            from {opacity: 0}
            to {opacity: 1}
        }

        @keyframes fadein {
            from {opacity: 0}
            to {opacity: 1}
        }

        @-webkit-keyframes fadeout {
            from {opacity: 1}
            to {opacity: 0}
        }

        @keyframes fadeout {
            from {opacity: 1}
            to {opacity: 0}
        }

        @-webkit-keyframes fadeinout {
            0%,100% { opacity: 0; }
            50% { opacity: 1; }
        }

        @keyframes fadeinout {
            0%,100% { opacity: 0; }
            50% { opacity: 1; }
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {
            .text {font-size: 11px}
        }

        /* Chrome, Safari, Opera */
        @-webkit-keyframes slidein {
            0%   {left: 0px; top: -800px;}
            100% {left: 0px; top: 0px;}
        }

        @keyframes slidein {
            0%   {left: 0px; top: -800px;}
            100% {left: 0px; top: 0px;}
        }

        @keyframes slideout {
            0%   {left: 0px; top: 0px;}
            100% {left: 0px; top: 800px;}
        }

        @-webkit-keyframes slideout {
            0%   {left: 0px; top: 0px;}
            100% {left: 0px; top: 800px;}
        }

        @-webkit-keyframes slideleft {
            0%   {left: -1000px; top: 0px;}
            100% {left: 0px; top: 0px;}
        }

        @keyframes slideleft {
            0%   {left: -1000px; top: 0px;}
            100% {left: 0px; top: 0px;}
        }

        @keyframes slideright {
            0%   {left: 0px; top: 0px;}
            100% {left: 1000px; top: 0px;}
        }

        @-webkit-keyframes slideright {
            0%   {left: 0px; top: 0px;}
            100% {left: 1000px; top: 0px;}
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

        video::-webkit-media-controls-overlay-play-button {
            display: none !important;
            opacity: 0;
        }

        video::-webkit-media-controls-play-button {
            display: none !important;
        }

        video::-webkit-media-controls{
            display: none !important;
            -webkit-appearance: none !important;
            opacity: 0;
        }

        *::-webkit-media-controls-panel {
            display: none!important;
            -webkit-appearance: none;
        }

        /* Old shadow dom for play button */

        *::-webkit-media-controls-play-button {
            display: none !important;
            -webkit-appearance: none;
        }

        /* New shadow dom for play button */

        /* This one works! */

        *::-webkit-media-controls-start-playback-button {
            display: none !important;
            -webkit-appearance: none;
        }
    </style>


<div id="slides" class="slides">
    <?php
        $controls = [];
        $type = \common\components\FHtml::getRequestParam('type');
        $slideId = \common\components\FHtml::getRequestParam('slide_id');
        //$blank_img = \common\components\FHtml::currentDomain() . '/' . str_replace('/backend/web/index.php', '', \common\components\FHtml::createUrl('/backend/web/upload/www/blank.png'));
        $blank_img = \common\components\FHtml::getImageUrl('blank.png', 'www');
        foreach ($slides as $i => $slide) {
            $command = is_object($slide) ? $slide->command : (isset($slide['command']) ? $slide['command'] : '');
            if ($command == 'text')
                $command = 'marquee';

            if (!empty($type) && $command != $type) {
                unset($slides[$i]);
                continue;
            }

            if (!empty($slideId) && $slide->id != $slideId) {
                unset($slides[$i]);
                continue;
            }

            $slide_duration =  is_object($slide) ? $slide->file_duration : (isset($slide['file_duration']) ? $slide['file_duration'] : '');
            $slide_description =  is_object($slide) ? $slide->description : (isset($slide['description']) ? $slide['description'] : '');
            $slide_file =  is_object($slide) ? $slide->file : (isset($slide['file']) ? $slide['file'] : '');
            $slide_id =  is_object($slide) ? $slide->id : (isset($slide['id']) ? $slide['id'] : '');

            $description = \common\components\FHtml::showHtml($slide_description);
            $url  = \backend\modules\smartscreen\Smartscreen::getFileUrl($slide_file, 'smartscreen-file');
            if (empty($url) && starts_with($slide_description, 'http')) {
                $url = $slide_description;
                $description = '';
            }
            if (in_array($command, ['video', 'image', 'youtube', 'facebook'])) {
                if (empty($url) || ends_with($url, 'no_image.jpg')) {
                    unset($slides[$i]);
                    continue;
                }
            } else {
                if (empty($description)) {
                    unset($slides[$i]);
                    continue;
                }
            }
            $slide['command'] = $command;
            $slide['description'] = $description;
            $slide['url'] = $url;

            $slides[$i] = $slide;
        }
        $i = 0;
        if (empty($slides)) {
            $slide = new \backend\modules\smartscreen\models\SmartscreenSchedules();
            $slide->command = 'text';
            $slide->description = \common\components\FHtml::showCurrentLogo('', '100px') . '&nbsp;' . strtoupper(\common\components\FHtml::settingCompanyName() . ' - ' . \common\components\FHtml::settingCompanyDescription());
            $slides = [$slide];
        }
        foreach ($slides as $slide) {
            $i ++;
            $generateControl = false;

            $command = is_object($slide) ? $slide->command : (isset($slide['command']) ? $slide['command'] : '');
            if ($command == 'text')
                $command = 'marquee';
            $slide_duration =  is_object($slide) ? $slide->file_duration : (isset($slide['file_duration']) ? $slide['file_duration'] : '');
            $description =  is_object($slide) ? $slide->description : (isset($slide['description']) ? $slide['description'] : '');
            $slide_file =  is_object($slide) ? $slide->file : (isset($slide['file']) ? $slide['file'] : '');
            $slide_id =  is_object($slide) ? $slide->id : (isset($slide['id']) ? $slide['id'] : '');
            $url =  is_object($slide) ? $slide->url : (isset($slide['url']) ? $slide['url'] : '');

            if (empty($slide_duration))
                $duration = 0;
            else if ($slide->file_kind == 'second')
                $duration = $slide_duration * 1000;
            else if ($slide->file_kind== 'time')
                $duration = $slide_duration * 60 * 1000;
            else
                $duration = 0;

            if ($command != 'video' && $duration == 0 && count($slides) > 1)
                $duration = 5000;

            $control = '';

            if ($command == 'marquee') {
                $control = "<marquee onclick=\"pauseSlide()\" style=\"font-size:8vh;padding-top:10%\">$description</marquee>";
                $description = '';
            } else if ($command == 'video') {
                $control =  "<video onclick=\"pauseSlide()\" width=\"100%\" height=\"100%\" preload=\"none\" poster=\"$blank_img\" loop><source src=\"$url\" type=\"video/mp4\"></video>";
            } else if ($command == 'image') {
                $control =  "<img onclick=\"pauseSlide()\" src=\"$url\" width=\"100%\" height=\"100%\" style=\"object-fit:cover;width: 100%;height: 100%;\">";
            } else if ($command == 'youtube') {
                $control =  "<iframe onclick=\"pauseSlide()\" src=\"$url?autoplay=1&loop=1&rel=0&controls=0&hd=1&showinfo=0&enablejsapi=1\" frameborder=0 width=\"100%\" height=\"100%\" allowfullscreen></iframe>";
            } else if ($command == 'url') {
                $control =  "<iframe onclick=\"pauseSlide()\" src=\"$url\" frameborder=0 width=\"100%\" height=\"100%\" allowfullscreen></iframe>";
            } else if ($command == 'embed') {
                $control =  "$description";
                $description = '';
            } else if ($command == 'facebook') {
                $url = urlencode($url);
                $url = "https://www.facebook.com/v2.3/plugins/video.php?allowfullscreen=true&autoplay=true&mute=0&container_width=800&href=$url&locale=en_US&sdk=joey";
                $control =  "<iframe onclick=\"pauseSlide()\" src=\"$url\" frameborder=0 width=\"100%\" height=\"100%\" allowfullscreen></iframe>";
            }

            $controls[] = $control;
            ?>

    <div class="slide" duration="<?= $duration ?>" type="<?= $command ?>" style="" id="<?= $slide_id ?>">
        <div>
            <?php if ($generateControl) {
                echo $control;
            }?>
        </div>
        <div id="slide_number" class="numbertext"><?= $i ?> / <?= count($slides) ?></div>
        <?php if (!empty($description)) { ?>
        <div id="slide_description" class="text"><?= $description ?></div>
        <?php  } ?>
    </div>

    <?php }?>

</div>
    <div id="slide_status" class="numbertext" style="z-index:90000 !important;"></div>

<script>
    //settings
    var slideIndex = 0;
    var slides = document.getElementsByClassName("slide");
    var controls = [];
    var timeoutId;
    var timeoutId1;
    var pause = false;
    var defaultTransitionIn =  'fade-in'; //'fade-in', 'slide-in', 'slide-left';
    var defaultTransitionOut = 'fade-out'; //'fade-out';
    var currentVideoTime = 1;
    var transition_when_slide_out = false;
    var transition_duration = 1000; //default: 2000
    var clearTimeOut = true;
    var videoIndex = 0;
    var videos = [
        'http://103.63.109.98/smartads//applications/smartads/upload/smartscreen-file/smartscreen_content_80_1608922570.mp4',
        'http://103.63.109.98/smartads//applications/smartads/upload/smartscreen-file/smartscreen_content_63_1608904690.mp4'
    ];
    var playPromise;


    <?php foreach ($controls as $control) {
        echo "controls.push('$control');\n";
    } ?>
    //console.table(controls);

    window.onload = function() {
        document.addEventListener('swiped-left', function(e) {
            pause = false;
            currentVideoTime = 0;
            slideIndex++;
            if (slideIndex > slides.length - 1) { slideIndex = 0 }
            showSlides(slideIndex);
        });

        document.addEventListener('swiped-right', function(e) {
            pause = false;
            currentVideoTime = 0;
            slideIndex = slideIndex - 1;
            if (slideIndex < 0) {
                slideIndex = slides.length - 1;
            }
            showSlides(slideIndex);
        });

        document.addEventListener('swiped-up', function(e) {
            pause = false;
            currentVideoTime = 0;
            if (slideIndex == slides.length - 1)
                slideIndex = 0;
            else
                slideIndex = slides.length - 1;
            showSlides(slideIndex);
        });

        document.addEventListener('swiped-down', function(e) {
            pause = false;
            currentVideoTime  = 0;
            if (slideIndex == 0)
                slideIndex =  slides.length -1;
            else
                slideIndex = 0;
            showSlides(slideIndex);
        });

        showSlides(0);
    }

    //window.addEventListener('resize', resize, false);
    //resize();

    function resizeObject(video) {
        //console.log(video);
        videoRatio = video.height / video.width;
        windowRatio = window.innerHeight / window.innerWidth; /* browser size */

        if (windowRatio < videoRatio) {
            if (window.innerHeight > 50) { /* smallest video height */
                video.height = window.innerHeight;
            } else {
                video.height = 50;
            }
        } else {
            video.width = window.innerWidth;
        }
    }

    function resize() {
        var videos = document.getElementsByTagName("video");
        for (i = 0; i < videos.length; i++) {
            resizeObject(videos[i]);
        }

        var images = document.getElementsByTagName("img");
        for (i = 0; i < images.length; i++) {
            resizeObject(images[i]);
        }
    };

    function pauseVideo(video) {
        video.autoplay = false;
        if (playPromise !== undefined) {
            playPromise.then(_ => {
                video.pause();
            }).catch(error => {
                });
        } else {
            video.pause();
        }
    }

    function playVideo(video) {
        playPromise = video.play();
    }

    function playVideoFromUrl(video, url) {
        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                video.srcObject = blob;
                playPromise = video.play();
                return playPromise;
            })
            .then(_ => {
                // Video playback started ;)
            })
            .catch(e => {
                // Video playback failed ;(
            })
    }

    function pauseSlide() {
        if (!pause)
            pause = true;
        else
            pause = false;

        if (timeoutId > 0 && clearTimeOut) {
            clearTimeout(timeoutId);
        }
        if (timeoutId1 > 0 && clearTimeOut) {
            clearTimeout(timeoutId1);
        }
        showSlides(slideIndex);
        if (pause) {
            var slide_status = document.getElementById('slide_status');
            slide_status.innerHTML = '<div style="background-color:red"> &nbsp;Paused&nbsp; </div>';
        } else {
            var slide_status = document.getElementById('slide_status');
            slide_status.innerHTML = '';
        }
    }

    (function(window,document){'use strict';if('initCustomEvent'in document.createEvent('CustomEvent')){window.CustomEvent=function(event,params){params=params||{bubbles:false,cancelable:false,detail:undefined};var evt=document.createEvent('CustomEvent');evt.initCustomEvent(event,params.bubbles,params.cancelable,params.detail);return evt;};window.CustomEvent.prototype=window.Event.prototype;}
        document.addEventListener('touchstart',handleTouchStart,false);document.addEventListener('touchmove',handleTouchMove,false);document.addEventListener('touchend',handleTouchEnd,false);var xDown=null;var yDown=null;var xDiff=null;var yDiff=null;var timeDown=null;var startEl=null;function handleTouchEnd(e){if(startEl!==e.target)return;var swipeThreshold=parseInt(startEl.getAttribute('data-swipe-threshold')||'20',10);var swipeTimeout=parseInt(startEl.getAttribute('data-swipe-timeout')||'500',10);var timeDiff=Date.now()-timeDown;var eventType='';if(Math.abs(xDiff)>Math.abs(yDiff)){if(Math.abs(xDiff)>swipeThreshold&&timeDiff<swipeTimeout){if(xDiff>0){eventType='swiped-left';}
        else{eventType='swiped-right';}}}
        else{if(Math.abs(yDiff)>swipeThreshold&&timeDiff<swipeTimeout){if(yDiff>0){eventType='swiped-up';}
        else{eventType='swiped-down';}}}
            if(eventType!==''){startEl.dispatchEvent(new CustomEvent(eventType,{bubbles:true,cancelable:true}));if(console&&console.log)console.log(eventType+' fired on '+startEl.tagName);}
            xDown=null;yDown=null;timeDown=null;}
        function handleTouchStart(e){if(e.target.getAttribute('data-swipe-ignore')==='true')return;startEl=e.target;timeDown=Date.now();xDown=e.touches[0].clientX;yDown=e.touches[0].clientY;xDiff=0;yDiff=0;}
        function handleTouchMove(e){if(!xDown||!yDown)return;var xUp=e.touches[0].clientX;var yUp=e.touches[0].clientY;xDiff=xDown-xUp;yDiff=yDown-yUp;}}(this,document));

    const loadVideo = file => new Promise((resolve, reject) => {
        try {
            let video = document.createElement('video')
            video.preload = 'none'

            video.onloadedmetadata = function () {
                resolve(this)
            }

            video.onerror = function () {
                reject("Invalid video. Please select a video file.")
            }

            video.src = window.URL.createObjectURL(file)
        } catch (e) {
            reject(e)
        }
    });

    function getControlFromSlide(slide) {
        return slide.children[0].children[0];
    }

    function startSlide(slide, type) {
        slide.style.display = "block";
        var control = controls[slideIndex];
        if (control) {
            slide.children[0].innerHTML = control;
        }
    }

    function startTransitionSlide(slide) {
        var transition_in = slide.getAttribute("transition-in");
        var transition = slide.getAttribute("transition");

        if (transition_in == '' || transition_in == null)
            transition_in = transition == null ? defaultTransitionIn : transition;
        slide.setAttribute('class', 'slide ' + transition_in);
    }

    function stopTransitionSlide(slide) {

    }

    function stopSlide(slide, type) {
        slide.style.display = "none";
        slide.children[0].innerHTML = '';
        if (type == 'video') {
            var video = getControlFromSlide(slide);
            if (video) {
                pauseVideo(video, playPromise);
                if (!pause)
                    video.currentTime = 0;
            }
        }
    }

    function showSlides(_slideIndex = -1) {

        var stopVideo = (e) => {
            video = e.target;
            //console.log(video);
            video.pause();
            if (!pause)
                video.currentTime = 0;
            video.removeEventListener('ended', this);
            video.removeEventListener('timeupdate', this);

            showSlides();
        };

        var updateTimeVideo = (e) => {
            video = e.target;
            currentVideoTime = video.currentTime;
            //console.log(currentVideoTime);
        };

        var i;
        var slide;
        var type;
        var transition_out;
        var transition_in;
        var transition;
        var duration;

        if (timeoutId > 0 && clearTimeOut) {
            clearTimeout(timeoutId);
        }
        if (timeoutId1 > 0 && clearTimeOut) {
            clearTimeout(timeoutId1);
        }

        for (i = 0; i < slides.length; i++) {
            slide = slides[i];
            //console.log(slide);
            type = slide.getAttribute("type");

            startTransitionSlide(slide);
            stopSlide(slide, type);
        }
        if (_slideIndex >= 0) {
            slideIndex = _slideIndex;
        } else {
            slideIndex++;
            if (slideIndex > slides.length - 1) {
                slideIndex = 0
            }
        }

        slide = slides[slideIndex];

        duration = slide.getAttribute("duration");
        transition_out = slide.getAttribute("transition-out");
        transition_in = slide.getAttribute("transition-in");
        transition = slide.getAttribute("transition");

        if (transition_in == '' || transition_in == null)
            transition_in = transition == null ? defaultTransitionIn : transition;

        if (transition_out == '' || transition_out == null)
            transition_out = transition == null ? defaultTransitionOut : transition;

        type = slide.getAttribute("type");

        startSlide(slide, type);

        if (type == 'video') {
            video = getControlFromSlide(slide);
            video.preload = 'none';
            video.controls = false;
            video.removeAttribute('controls');

            if (duration == 0 && slides.length > 1) {
                video.loop = false; //if not --> never ended
                video.autoplay = true;
                video.addEventListener('durationchange', function() {
                    //console.log(this.parentNode);
                    this.parentNode.parentNode.setAttribute("duration", (this.duration * 1000));
                });
                video.addEventListener('ended', stopVideo, false);
                video.addEventListener("timeupdate", updateTimeVideo, false);

                var isPlaying = video.currentTime > 0 && !video.paused && !video.ended
                    && video.readyState > 2;

                if (!isPlaying) {
                    if (!pause) {
                        video.currentTime = currentVideoTime;
                        video.play();
                    } else {
                        video.pause();
                        video.currentTime = currentVideoTime;
                    }
                } else {
                    if (pause) {
                        video.pause();
                        video.currentTime = currentVideoTime;
                    }
                }
            } else {
                video.addEventListener("timeupdate", updateTimeVideo, false);
                video.autoplay = true;
                var isPlaying = video.currentTime > 0 && !video.paused && !video.ended
                    && video.readyState > 2;

                if (!isPlaying) {
                    if (!pause) {
                        video.currentTime = currentVideoTime;
                        video.play();
                    } else {
                        video.pause();
                        video.currentTime = currentVideoTime;
                    }
                } else {
                    if (pause) {
                        video.pause();
                        video.currentTime = currentVideoTime;
                    }
                }
            }
        } else if (type == 'marquee') {
            text = getControlFromSlide(slide);
            text.start();
        }

        if (duration > 0 && !pause && slides.length > 1) {
            //console.log('Clear Time Out' + timeoutId);
            timeoutId = setTimeout(() => {

                slide.setAttribute('class', 'slide ' + transition_out);

                if (slides.length == 1 || transition_duration == 0) { //only 1 item -> dont need transition
                    showSlides();
                } else {
                    timeoutId1 = setTimeout(() => {
                        //finish slide, start new slide
                        showSlides();
                    }, transition_duration);
                }
                //console.log('-- timeoout id2: ' + timeoutId1);

                //clearTimeout(timeoutId1);
                // if (type == 'marquee') {
                //     marquee = getControlFromSlide(slide);
                //     marquee.stop();
                //     setTimeout(()=> {
                //        console.log(marquee.innerHTML);
                //     }, 2000);
                // }
            }, duration);
            //console.log('Timeoout Id: ' + timeoutId);
        }

        timeoutId = setTimeout(() => {

            //slide.setAttribute('class', 'slide ' + transition_out);
            slide = slides[slideIndex];
            video = getControlFromSlide(slide);

            console.log(videoIndex);
            //var source = document.createElement('source');

            //source.setAttribute('src', videos[videoIndex]);
            //video.appendChild(source);
            //video.src = videos[videoIndex];
            //video.autoplay = true;
            //video.currentTime = 0;
            video.load();
            fetch(videos[videoIndex])
                .then(response => response.blob())
                .then(blob => {
                    video.srcObject = blob;
                    return video.play();
                })
                .then(_ => {
                    // Video playback started ;)
                })
                .catch(e => {
                    // Video playback failed ;(
                })

            videoIndex += 1;
            if (videoIndex > videos.length - 1)
                videoIndex = 0;
            showSlides();
        }, duration);
    }
</script>

<?php } ?>

</body>
</html>
