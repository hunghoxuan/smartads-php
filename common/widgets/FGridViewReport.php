<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 08/28/2018
 * Time: 11:34
 */

namespace common\widgets;

use common\components\FExcel;
use common\components\FHtml;
use common\components\FModel;
use common\components\FQuery;
use common\components\FReport;
use common\widgets\fchart\Chart;
use common\widgets\fchart\models\ChartPie;
use Yii;
use yii\db\Query;
use yii\helpers\StringHelper;


/**
 * Class FGridViewReport
 * @package common\widgets
 */
class FGridViewReport extends BaseWidget
{

	/**
	 * @var string
	 */
	public $toolbar;
	/**
	 * @var int
	 */
	public $itemSize;
	/**
	 * @var array
	 */
	public $actionColumn;
	/**
	 * @var string
	 */
	public $emptyMessage;
	/**
	 * @var string
	 */
	public $layout;
	/**
	 * @var array
	 */
	public $field_date = [];
	/**
	 * @var string
	 */
	public $field_group_by = [];
	/**
	 * @var array
	 */
	public $group_type = [];

	public $group_type_array = [];

	/**
	 * @var array
	 */
	public $group_date = ['week', 'month'];

	/**
	 * @var array
	 */
	protected $dataList = [];
	/**
	 * @var array
	 */
	protected $dataChart = [];
	/**
	 * @var array
	 */
	public $dates = [];

	/**
	 * @var array
	 */
	protected $columns;

	/**
	 * @return int
	 */
	public function getItemsCount() {
		return isset($this->dataProvider) ? $this->dataProvider->getTotalCount() : 0;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function run() {
	    if (!isset($this->model))
		    $this->model = FModel::createModel($this->object_type);

		$this->prepareDataList();

		if (!isset($this->model->start_date)) {
			$this->model->start_date = FHtml::Now();
		}
		if (!isset($this->model->end_date)) {
			$this->model->end_date = FHtml::Now();
		}

		$report_type = FHtml::getRequestParam('report_type');
		if ($report_type == 'export') {
		    //FHtml::downloadContent(FExcel::exportToCSV($this->dataList));
			ob_end_clean();
			$content = ob_get_clean();
			ob_start();
			FExcel::exportToCSV($this->dataList);
			ob_end_flush();
			exit();
		}

		if (empty($this->field_date))
        {
            $input = FHtml::showEmptyMessage();
        } else {
            $input = $this->render('report', [
                'field_date' => $this->field_date,
                'field_group_by' => $this->field_group_by,
                'group_type' => $this->group_type,
                'group_type_array' => $this->group_type_array,
                'group_date' => $this->group_date,
                'model' => $this->model,
                'object_type' => $this->object_type,
                'dates' => $this->dates,
                'dataList' => $this->getDataList(),
                'dataChart' => $this->mixDataChart(),
                'time_range' => $this->aryTimeType()
            ]);
        }
		$layout  = $this->layout;
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
		$this->columns = $columns;
	}

	protected function prepareDataList() {
		$field_date = FHtml::getRequestParam('group_date');
		$group_by   = FHtml::getRequestParam('group_by');
		$date_type  = FHtml::getRequestParam('date_type');
		$type       = FHtml::getRequestParam('type');
		$time_range = FHtml::getRequestParam('time_range');
		$startDate  = FHtml::getRequestParam('start_date');
		$endDate    = FHtml::getRequestParam('end_date');

        if (empty($this->field_date)) {
            $fields = ['created_date', 'modified_date'];
            foreach ($fields as $field) {
                if (FHtml::field_exists($this->model, $field))
                    $this->field_date[] = $field;
            }
        }

        if (empty($this->field_group_by)) {
            $fields = ['user_id', 'product_id', 'category_id', 'status', 'payment_status', 'type'];
            foreach ($fields as $field) {
                if (FHtml::field_exists($this->model, $field))
                    $this->field_group_by[] = $field;
            }
        }

        if (empty($this->group_date)) {
            $fields = ['created_date', 'modified_date'];
            foreach ($fields as $field) {
                if (FHtml::field_exists($this->model, $field))
                    $this->group_date[] = $field;
            }
        }

        if (empty($this->group_type)) {
            $fields = ['total', 'grand_total', 'order_total'];
            $this->group_type[] = 'ALL';
            $this->group_type[] = 'COUNT(*)';

            foreach ($fields as $field) {
                if (FHtml::field_exists($this->model, $field)) {
                    $this->group_type[] = "SUM($field)";
                    $this->group_type[] = "AVG($field)";
                }
            }
        }

        if (empty($this->group_type_array)) {

            $group_type_array = [];
            foreach ($this->group_type as $group_type) {
                if ($group_type == 'ALL')
                    $group_type1 = FHtml::t('common', 'All');
                else if ($group_type == 'COUNT(*)')
                    $group_type1 = FHtml::t('common', 'Quantity');
                else {
                    $group_type1 = FHtml::strReplace($group_type, ['SUM' => '', 'AVG' => '', 'COUNT' => '', '(' => '', ')' => '']);
                    $group_type1 = str_replace($group_type1, FHtml::getFieldLabel($this->model, $group_type1), $group_type);
                }

                $group_type_array = array_merge($group_type_array, [$group_type => $group_type1]);
            }

            $this->group_type_array = $group_type_array;
        }

        if (empty($field_date) && empty($this->field_date))
            return;

        if (empty($group_by) && empty($this->field_group_by))
            return;

        if (empty($date_type) && empty($this->group_date))
            return;

		if (empty($field_date)) {
			$field_date = $this->field_date[0];
		}

		if (empty($group_by)) {
			$group_by = $this->field_group_by[0];
		}

		if (empty($date_type)) {
			$date_type = $this->group_date[0];
		}

		$date_type = strtoupper($date_type);


		if (empty($type)) {
			$type = $this->group_type[0];
		}

		$model = &$this->model;
		$this->caculTime($time_range, $startDate, $endDate, $model);

		$this->model->time_range = $time_range;
		$this->model->group_by   = $group_by;
		$this->model->group_date = $field_date;
		$this->model->date_type  = strtolower($date_type);
		$this->model->type       = $type;

		if ($date_type == 'WEEK') {
			$date_type = "WEEKOFYEAR";
		}

		if ($type == "ALL") {
			$type = $this->group_type;
			array_shift($type);
		}
		else {
			$type = [$type];
		}

		foreach ($type as $index => $item) {
			$field_total = $item;
			$query       = new  FQuery();
			$query->from($this->object_type);
			$query->select("$field_total as total, $field_date, $group_by as name, YEAR($field_date) as year, $date_type($field_date) as dates");
			$query->groupBy(["YEAR($field_date)", "$date_type($field_date)", $group_by]);
			$query->orderBy("$field_date ASC");
			if (!empty($startDate) && !empty($endDate)) {
				$query->where("$field_date > $startDate AND $field_date < $endDate");
			}
			$dataList = $query->all();
			$this->mixDataList($dataList, $item);
		}
	}

	/**
	 * @param        $dataList
	 * @param string $type
	 * @return array
	 */
	protected function mixDataList($dataList, $type = '') {
		//$dataList    = $this->getDataList();
		$dates       = array_column($dataList, 'dates');
		$dates       = array_unique($dates);
		$dates       = Chart::getFullDate($dates);
		$this->dates = $dates;
		$data        = [];
		foreach ($dataList as $index => $item) {
			if (!isset($data[$item['name']])) {
				$data[$item['name']] = [
					'name'  => $item['name'],
					'total' => [$item['dates'] => floatval($item['total'])]
				];
			}
			else {
				$data[$item['name']]['total'][$item['dates']] = floatval($item['total']);
			}
		}

		return $this->dataList[$type] = $data;
	}

	/**
	 * @return array
	 */
	protected function mixDataChart() {
		$dataList = $this->getDataList();
		//FHtml::var_dump($dataList);die;
		//FHtml::var_dump($dates);die;
		$dates = $this->dates;

		// duyet danh sach data duoc phan theo group_type
		foreach ($dataList as $key => &$list) {
			//duyet danh sach duoc phan theo group_by
			foreach ($list as $k_l => &$item) {
				foreach ($dates as $k => $date) {
					// check cac date chua co du lieu
					if (!isset($item['total'][$date])) {
						$item['total'][$date] = 0;
					}
				}
				ksort($item['total']);
				$item['total'] = array_values($item['total']);
			}
		}

		//FHtml::var_dump($dataList);die;
		return $this->dataChart = $dataList;
	}

	/**
	 * @param string $timeType
	 * @param        $startDate
	 * @param        $endDate
	 * @param self   $model
	 * @return bool
	 * tinh toan thoi gian bao cao
	 */
	public function caculTime($timeType, &$startDate, &$endDate, &$model = null) {
		return FReport::getTimeRange($timeType, $startDate, $endDate, $model);
	}

	/**
	 * @param bool $get_all_day
	 * @return array
	 */
	public function aryTimeType($get_all_day = true) {
		return FReport::getTimeRangeArray($get_all_day);
	}
}