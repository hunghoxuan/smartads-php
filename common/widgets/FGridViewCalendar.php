<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 08/28/2018
 * Time: 11:34
 */

namespace common\widgets;

use common\components\FHtml;
use common\widgets\fchart\Chart;
use common\widgets\fchart\models\ChartPie;
use Yii;
use yii\base\Widget;
use yii\db\ActiveQuery;
use yii\helpers\StringHelper;
use yii\web\View;


class FGridViewCalendar extends BaseWidget
{
	/**
	 * @var array
	 */
	protected $columns = [];
	public    $toolbar;
	public    $itemSize;
	public    $actionColumn;
	public    $emptyMessage;
	public    $layout;
	/**
	 * @var string
	 */
	public $url = '';
	//public $object_type;
	//public $dataProvider;
	//public $display_type;

	/**
	 * @var array
	 */
	protected $dataList = [];
	public    $action   = 'view';

	public function init() {

		parent::init(); // TODO: Change the autogenerated stub
	}

	public function getItemsCount() {
		return isset($this->dataProvider) ? $this->dataProvider->getTotalCount() : 0;
	}

	public function render($view, $params = [], $widgetRender = true) {
		//$columns        = $this->getColumns();
		$this->dataList = $this->dataProvider->getModels();
		$events         = [];
		foreach ($this->getDataList() as $key => $model) {
			$events[] = [
				'id'    => $model->id,
				'title' => $model->name,
				'start' => $model->created_date
			];
		}
		$html   = "<div id='{$this->object_type}_calendar' style='padding-top:25px'></div>";
		$html   .= $this->renderModal();
		$events = json_encode($events, JSON_NUMERIC_CHECK);
		$this->renderScript($events);

		return $html;
	}

	public function run() {
		$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();

		FHtml::currentView()->registerJsFile($baseUrl . "/plugins/fullcalendar/moment.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
		FHtml::currentView()->registerJsFile($baseUrl . "/plugins/fullcalendar/fullcalendar.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
		//FHtml::currentView()->registerCssFile($baseUrl . "/plugins/fullcalendar/fullcalendar.min.css", ['depends' => [\yii\web\JqueryAsset::className()]]);

		$layout  = $this->layout;
		$input   = $this->render($this->display_type);
		$summary = '';

		echo FHtml::strReplace($layout, ['{items}' => $input, '{summary}' => $summary, '{pager}' => null]);
	}


	/**
	 * @return array
	 */
	public function getDataList() {
		return $this->dataList;
	}

	/**
	 * @param array $dataList
	 */
	public function setDataList($dataList) {
		$this->dataList = $dataList;
	}

	/**
	 * @return array
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * @param array $columns
	 */
	public function setColumns($columns) {
		//	    foreach ($columns as $i => $column) {
		//            if (!FHtml::field_exists($this->object_type, $column))
		//                unset($columns[$i]);
		//        }
		$this->columns = $columns;
	}

	/**
	 * @return string
	 */
	public function createToolbar() {
		if (empty($this->toolbar)) {
			$currentRole  = FHtml::getCurrentRole();
			$moduleName   = FHtml::currentModule();
			$createButton = '';
			if (FHtml::isInRole('', 'create', $currentRole)) {
				$createButton = FHtml::a('<i class="glyphicon glyphicon-plus"></i>&nbsp;' . FHtml::t('common', 'Create'), ['create'], [
					'role'      => $this->params['editType'],
					'data-pjax' => $this->params['isAjax'] == true ? 1 : 0,
					'title'     => FHtml::t('common', 'title.create'),
					'class'     => 'btn btn-success',
					'style'     => 'float:left;'
				]);
			}

			$this->toolbar = $createButton;
		}

		return "<div class='row' style='margin-left:10px;padding-bottom:5px;margin-right:10px'>" . $this->toolbar . "</div>";
	}

	/**
	 * @return string
	 */
	public function renderEmpty() {
		return "<div class='row clear-both'>" . "<div style='padding:10px'>" . FHtml::showEmptyMessage($this->emptyMessage) . "</div></div>";
		//return "<div class='row clear-both'>" . self::createToolbar() . "<div style='padding:10px'>" . FHtml::showEmptyMessage() . "</div></div>";
	}

	public function renderScript($events) {
		$now = FHtml::Now();
		FHtml::currentModule();
		$url     = $this->createUrl();
		$script  = <<<JS
$('#{$this->object_type}_calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'prevYear,month,agendaDay,agendaWeek,listDay,listWeek,month,nextYear'
    },
    defaultDate: '{$now}',
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    eventLimit: true, // allow "more" link when too many events
    eventReceive: function(event){
        //  console.log('eventReceive');
        // var title = event.title;
        // var start = event.start.format("YYYY-MM-DD HH:MM:SS");
        // $.ajax({
        //     url: 'process.php',
        //     data: 'type=new&title='+title+'&startdate='+start+'&zone='+zone,
        //     type: 'POST',
        //     dataType: 'json',
        //     success: function(response){
        //         event.id = response.eventid;
        //         $('#calendar').fullCalendar('updateEvent',event);
        //     },
        //     error: function(e){
        //         console.log(e.responseText);
        //     }
        // });
        // $('#calendar').fullCalendar('updateEvent',event);
    },
    eventClick: function(event, jsEvent, view) {
        url = "{$url}" + '?layout=no&id=' + event.id;
        //console.log(url);
        showModalIframe(url);
        //$("#ajaxCrubModal").modal("show");
        //$("#modalCalendar").find("#modalContent").load("{$url}?id=" + event.id);
        //$("#ajaxCrubModal").find("#modalIframe").attr('src', url);

         //window.open( "{$url}?id=" + event.id,'_blank');
        return false;
        // var title = prompt('Event Title:', event.title, {buttons: { Ok: true, Cancel: false}});
        // if (title){
        //     event.title = title;        
        //     console.log('eventClick');
        //     $.ajax({
        //         url: 'process.php',
        //         data: 'type=changetitle&title='+title+'&eventid='+event.id,
        //         type: 'POST',
        //         dataType: 'json',
        //         success: function(response){
        //             if(response.status === 'success') {
        //                 $('#calendar').fullCalendar('updateEvent',event);
        //             }
        //         },
        //         error: function(e){
        //             alert('Error processing your request: '+e.responseText);
        //         }
        //    });
        // }
    },
    eventDrop: function(event, delta, revertFunc) {
        // var id = event.id;
        // var title = event.title;
        // var start = event.start.format();
        // var end = (event.end == null) ? start : event.end.format();
        // console.log('drop');
        // $.ajax({
        //     url: 'process.php',
        //     data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+id,
        //     type: 'POST',
        //     dataType: 'json',
        //     success: function(response){
        //         if(response.status !== 'success') {
        //             revertFunc();
        //         }
        //     },
        //     error: function(e){
        //         revertFunc();
        //         alert('Error processing your request: '+e.responseText);
        //     }
        // });
    },
    events: $events
});
JS;
		$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();

		FHtml::currentView()->registerJs($script, View::POS_END);
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		if (empty($this->url)) {
			$module     = FHtml::currentModule();
			$controller = FHtml::currentController();
			$action     = $this->action;

			return $this->url = "$module/$controller/$action";
		}

		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	private function createUrl($params = []) {
		if (filter_var($this->getUrl(), FILTER_VALIDATE_URL)) {
			return $this->getUrl() . "?" . http_build_query($params);
		}

		return FHtml::createUrl($this->getUrl(), $params);
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function renderModal() {
	    return '';
		return "<div class=\"modal inmodal\" id=\"modalCalendar\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">
			    <div class=\"modal-dialog\">
			        <div class=\"modal-content animated fadeIn\">
			            <div class=\"modal-header\">
			                <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
			            </div>
			            <div class=\"modal-body\">
			                <div id='modalContent'></div>
			                <iframe id='modalIframe' frameborder='0' width='100%' height='680px' scrolling='yes'></iframe>
			            </div>            
			        </div>
			    </div>
			</div>";
	}
}