<?php

/**
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @version   3.1.2
 */

namespace common\widgets;

use common\components\FConfig;
use common\components\FHtml;
use kartik\base\Config;
use kartik\grid\GridView;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\jui\JuiAsset;
use yii\web\JsExpression;
use Closure;
use yii\widgets\LinkPager;
use yii2fullcalendar\yii2fullcalendar;

/**
 * Enhances the Yii GridView widget with various options to include Bootstrap specific styling enhancements. Also
 * allows to simply disable Bootstrap styling by setting `bootstrap` to false. Includes an extended data column for
 * column specific enhancements.
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class FCalendar extends yii2fullcalendar
{
    /**
     * @var array options the HTML attributes (name-value pairs) for the field container tag.
     * The values will be HTML-encoded using [[Html::encode()]].
     * If a value is null, the corresponding attribute will not be rendered.
     */
    public $options = array(
        'class' => 'fullcalendar',
    );

    /**
     * @var array clientOptions the HTML attributes for the widget container tag.
     */
    public $clientOptions = array(
        'weekends' => true,
        'default' => 'month',
        'editable' => true,
        'weekNumbers' => true,
        'selectable'  => true,
        'defaultView' => 'agendaWeek',
//        'eventResize' => new JsExpression("
//                function(event, delta, revertFunc, jsEvent, ui, view) {
//                    console.log(event);
//                }
//            "),
    );

    /**
     * Holds an array of Event Objects
     * @var array events of yii2fullcalendar\models\Event
     * @todo add the event class and write docs
     **/
    public $events = array();

    /**
     * Define the look n feel for the calendar header, known placeholders are left, center, right
     * @var array header format
     */
    public $header = array(
        'center'=>'title',
        'left'=>'prev,next today',
        'right'=>'month,agendaWeek,agendaDay'
    );
}
