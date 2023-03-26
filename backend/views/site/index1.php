<?php

use common\components\FHtml;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
//FHtml::execRemoteUrl('api/copyFile', ['folder' => "applications/stech", 'dest' => "applications/" . rand(1, 200) ], false);
//\common\components\FFile::copy("applications/stech", "applications/" . rand(1, 200)); die;
?>
<div class="site-index">
    <h3 class="page-title">
        <?= FHtml::t('common', 'Dashboard') ?>
        <small><?= FHtml::t('common', 'reports & statistics') ?></small>
    </h3>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">

                    </div>
                    <div class="desc">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                    </div>
                    <div class="desc">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">

                    </div>
                    <div class="desc">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number">

                    </div>
                    <div class="desc">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- END DASHBOARD STATS -->
    <div class="clearfix">
        <?php $arr = [11, 334, 444];
        ?>
    </div>
    <div id="trash">trash</div>
    <div class="clearfix">
    </div>

    <div id='calendar'></div>
</div>
<?php
$script = <<<JS
$('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaDay,agendaWeek,listDay,listWeek,month'
    },
    defaultDate: '2018-04-12',
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    eventLimit: true, // allow "more" link when too many events
    eventReceive: function(event){
                 console.log('eventReceive');
        var title = event.title;
        var start = event.start.format("YYYY-MM-DD HH:MM:SS");
        $.ajax({
            url: 'process.php',
            data: 'type=new&title='+title+'&startdate='+start+'&zone='+zone,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                event.id = response.eventid;
                $('#calendar').fullCalendar('updateEvent',event);
            },
            error: function(e){
                console.log(e.responseText);
            }
        });
        $('#calendar').fullCalendar('updateEvent',event);
    },
    eventClick: function(event, jsEvent, view) {
        var title = prompt('Event Title:', event.title, {buttons: { Ok: true, Cancel: false}});
        if (title){
            event.title = title;        
            console.log('eventClick');
            $.ajax({
                url: 'process.php',
                data: 'type=changetitle&title='+title+'&eventid='+event.id,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    if(response.status === 'success') {
                        $('#calendar').fullCalendar('updateEvent',event);
                    }
                },
                error: function(e){
                    alert('Error processing your request: '+e.responseText);
                }
           });
        }
    },
    eventDrop: function(event, delta, revertFunc) {
        var id = event.id;
        var title = event.title;
        var start = event.start.format();
        var end = (event.end == null) ? start : event.end.format();
        console.log('drop');
        $.ajax({
            url: 'process.php',
            data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+id,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                if(response.status !== 'success') {
                    revertFunc();
                }
            },
            error: function(e){
                revertFunc();
                alert('Error processing your request: '+e.responseText);
            }
        });
    },
    eventDragStop: function (event, jsEvent, ui, view) {
                 console.log('eventDragStop');
        if (isElemOverDiv()) {
            var con = confirm('Are you sure to delete this event permanently?');
            if(con === true) {
                $.ajax({
                    url: 'process.php',
                    data: 'type=remove&eventid='+event.id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        if(response.status === 'success') {
                            $('#calendar').fullCalendar('removeEvents');
                        }
                        $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
                    },
                    error: function(e){
                        alert('Error processing your request: '+e.responseText);
                    }
                });
            }
        }
    },
    events: [
        {
            title: 'All Day Event',
            start: '2018-04-01'
        },
        {
            title: 'Long Event',
            start: '2018-04-07',
            end: '2018-04-10'
        },
        {
            id: 999,
            title: 'Repeating Event',
            start: '2018-04-16 16:00:00'
        },
        {
            title: 'Conference',
            start: '2018-04-11',
            end: '2018-04-13'
        },
        {
            title: 'Lunch',
            start: '2018-04-12 12:00:00'
        },
        {
            title: 'Meeting',
            start: '2018-04-12'
        },
        {
            title: 'Happy Hour',
            start: '2018-04-12 17:30:00'
        },
        {
            title: 'Dinner',
            start: '2018-04-12 20:00:00'
        },
        {
            title: 'Birthday Party',
            start: '2018-04-13 07:00:00'
        },  
        {
            title: 'Click for Google',
            url: 'http://google.com/',
            start: '2018-04-28'
        }
    ]
});

function isElemOverDiv() {
    var trashEl = jQuery('#trash');
    var ofs = trashEl.offset();
    var x1 = ofs.left;
    var x2 = ofs.left + trashEl.outerWidth(true);
    var y1 = ofs.top;
    var y2 = ofs.top + trashEl.outerHeight(true);
    return currentMousePos.x >= x1 && currentMousePos.x <= x2 && currentMousePos.y >= y1 && currentMousePos.y <= y2;
}
var currentMousePos = {
    x: -1,
    y: -1
};

jQuery(document).on("mousemove", function (event) {
    currentMousePos.x = event.pageX;
    currentMousePos.y = event.pageY;
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>
<?php $this->registerJsFile('https://fullcalendar.io/releases/fullcalendar/3.9.0/lib/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile('https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<link rel="stylesheet" href="https://fullcalendar.io/releases/fullcalendar/3.9.0/fullcalendar.min.css" type="text/css">
