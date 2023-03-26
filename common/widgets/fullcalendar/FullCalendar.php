<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/10/2018
 * Time: 4:32 PM
 */

namespace common\widgets\fullcalendar;
use common\components\FHtml;
use common\widgets\BaseWidget;

/**
 * Class FullCalendar
 * @package common\widgets\fullcalendar
 */
class FullCalendar extends BaseWidget
{
    /**
     * @var string
     */
    public $header_left = '';
    /**
     * @var string
     */
    public $header_center = '';
    /**
     * @var string
     */
    public $header_right = '';
    /**
     * @var string
     */
    public $right = '';
    /**
     * @var bool
     */
    public $is_demo = false;
    /**
     * @var array
     */
    public $events = [];

    /* view group */
    const VIEW_GROUP_BASIC = 'month,basicWeek,basicDay';
    const VIEW_GROUP_LIST = 'listDay,listWeek,month';
    const VIEW_GROUP_LOCALES = 'month,agendaWeek,agendaDay,listMonth';
    const VIEW_GROUP_TIMEZONES = 'month,agendaWeek,agendaDay,listWeek';
    const VIEW_GROUP_GCAL = 'month,listYear';
    const VIEW_GROUP_THEME = 'month,agendaWeek,agendaDay,listMonth';
    const VIEW_GROUP_SELECTABLE = 'month,agendaWeek,agendaDay';
    const VIEW_GROUP_EXTERNAL_DRAGGING = 'month,agendaWeek,agendaDay';
    const VIEW_GROUP_BACKGROUND_EVENT = 'month,agendaWeek,agendaDay,listMonth';
    /*view schedule*/
    //const VIEW_SIMPLE = 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek';
    //const VIEW_VERTICAL_RESOURCE  = 'agendaDay,agendaTwoDay,agendaWeek,month';
    /*view schedule*/
    /* view */
    const VIEW_MONTH = 'month';
    const VIEW_BASIC_WEEK = 'basicWeek';
    const VIEW_BASIC_DAY = 'basicDay';
    const VIEW_LIST_DAY = 'listDay';
    const VIEW_LIST_WEEK = 'listWeek';
    const VIEW_LIST_MONTH = 'listMonth';
    const VIEW_LIST_YEAR = 'listYear';
    const VIEW_AGENDA_WEEK = 'agendaWeek';
    const VIEW_AGENDA_DAY = 'agendaDay';
    /* view */

    /*button*/
    const BUTTON_PREV_YEAR = 'prevYear';
    const BUTTON_NEXT_YEAR = 'nextYear';
    const BUTTON_PREV = 'prev';
    const BUTTON_MONTH = 'month';
    const BUTTON_NEXT = 'next';
    const BUTTON_DAY = 'day';
    const BUTTON_TODAY = 'today';
    const BUTTON_WEEK = 'week';
    const BUTTON_DEFAULT = 'prev,next today';
    /*button*/
    /**
     * @return string
     */
    public function run()
    {
        if (empty($this->display_type))
            $this->display_type = 'fullcalendar';

        return $this->render($this->display_type, [
            'is_demo' => $this->is_demo,
            'events' => $this->events,
            'header_left' => $this->header_left,
            'header_right' => $this->header_right,
            'header_center' => $this->header_center,
        ]);
    }

    public function init()
    {

        if (is_array($this->events) || is_object($this->events)) {
            $this->events = FHtml::encode($this->events);
        }
        elseif (!FHtml::is_json($this->events)) {
            $this->events = [];
        }

        if (empty($this->header_center)) {
            $this->header_center = 'title';
        }

        if (empty($this->header_left)) {
            $this->header_left = self::BUTTON_DEFAULT;
        }

        if (empty($this->header_right)) {
            $this->header_right = self::VIEW_GROUP_BASIC;
        }
    }
}

?>
