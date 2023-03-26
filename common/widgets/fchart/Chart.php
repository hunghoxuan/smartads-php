<?php

namespace common\widgets\fchart;

use common\components\FActiveQuery;
use common\components\FHtml;
use DateInterval;
use DatePeriod;
use DateTime;
use yii\base\Widget;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\web\JsExpression;

/**
 * Class Chart
 * @package common\widgets\fchart
 */
class Chart extends Widget
{
	/**
	 * @var array
	 */
	public $data      = array();
	public $title     = "";
	public $container = true;
	public $columns   = 4;

	/**
	 * @return string
	 */
	public function run() {
		return $this->render('chart', array('data' => $this->data, 'title' => $this->title, 'columns' => $this->columns, 'container' => $this->container));
	}

	/**
	 * @param         $all_items     | $result of query group by field
	 * @param array   $options       | colors array predefined
	 * @param string  $field         | field we use for count
	 * @param string  $field_total
	 * @param string  $time_field    | datetime format in db
	 * @param boolean $filter_type   | string $filter_type | 25 | yearly/monthly/daily
	 * @param boolean $year          | string $year | boolean
	 * @param boolean $month         | string $month | 08
	 * @param boolean $date          | string $date | 25
	 * @param boolean $important     | string $important | important field value
	 * @param         $is_percentage = true
	 * @return array
	 */
	public static function getPieData($all_items, $options, $field = 'status', $field_total = 'total', $time_field = '', $filter_type = false, $year = false, $month = false, $date = false, $important = false, $is_percentage = false) {
		$current_month = date('m', time());
		$current_year  = date('Y', time());
		$current_date  = date('d', time());

		if (!is_array($all_items)) {
			/* @var FActiveQuery $all_items */
			if ($important) {
				$all_items->orderBy("`$field` = '$important' DESC");
			}

			$date  = $date ? $date : $current_date;
			$month = $month ? $month : $current_month;
			$year  = $year ? $year : $current_year;

			if ($filter_type == "daily") {
				$all_items->where("DATE($time_field) = $date AND MONTH($time_field) = $month AND YEAR($time_field) = $year");
			}
			elseif ($filter_type == "monthly") {
				$all_items->where("MONTH($time_field) = $month AND YEAR($time_field) = $year");
			}
			elseif ($filter_type == 'yearly') {
				$all_items->where("YEAR($time_field) = $year");
			}
			$all_items = $all_items->select(new Expression('COUNT(*) AS total, ' .  $field))->groupBy([$field])->asArray()->all();

		}

		$item_count = null;
		$pie_data   = array();
		/* @var array $all_items */
		$total  = array_sum(array_column($all_items, $field_total));
		$values = array_column($options, 'value');
		$colors = array_column($options, 'color');

		$pieces_exist = array();

		foreach ($all_items as $item) {
		    if (!key_exists($field, $item) || !key_exists($field_total, $item))
		        continue;
			$key            = $item[$field];
			$pieces_exist[] = $key;
			$item_count     = $item[$field_total];
			if ($is_percentage) {
				$item_count = $item_count / $total;
			}
			$item_data = [
				'name' => ucwords($key),
				'y'    => (float) $item_count
			];

			if (count($colors) != 0) {
				$item_data['color'] = new JsExpression("getColor['$key']");
			}

			if (isset($important) && $important == $key) {
				$item_data = array_merge($item_data, ['sliced' => true, 'selected' => true]);
			}
			$pie_data [] = $item_data;
		}

		$not_appear = array_diff($values, $pieces_exist);

		foreach ($not_appear as $invisible) {
			$invisible_data = ['name' => ucwords($invisible), 'y' => null];
			if (count($colors) != 0) {
				$invisible_data['color'] = new JsExpression("getColor['$invisible']");
			}
			$pie_data[] = $invisible_data;
		}

		return $pie_data;
	}

	/**
	 * @param       $query
	 * @param       $field
	 * @param       $time_field
	 * @param       $filter_type
	 * @param bool  $year
	 * @param bool  $month
	 * @param bool  $date
	 * @param array $condition
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public static function getAreaData($query, $field, $time_field, $filter_type, $year = false, $month = false, $date = false, $condition = []) {
		/* @var ActiveQuery $query */
		$connection = \Yii::$app->db;
		$offset     = date('P');
		$connection->createCommand("SET time_zone = '$offset'")->execute();

		$current_month = date('m', time());
		$current_year  = date('Y', time());
		$current_date  = date('d', time());

		$date  = $date ? $date : $current_date;
		$month = $month ? $month : $current_month;
		$year  = $year ? $year : $current_year;

		$condition = array_merge($condition, ["FROM_UNIXTIME(`$time_field`, '%Y')" => $year]);
		if ($filter_type == "monthly") {
			$condition = array_merge($condition, ["FROM_UNIXTIME(`$time_field`, '%m')" => $month]);
		}
		elseif ($filter_type == "daily") {
			$condition = array_merge($condition, ["FROM_UNIXTIME(`$time_field`, '%d')" => $date]);
		}

		$all_items = $query->select(["sum($field) as total", "time"])->where($condition)->groupBy(new Expression("FROM_UNIXTIME(`$time_field`, '%Y-%m-%d')"))->asArray()->all();

		$area_data = array();
		/* @var array $search */

		foreach ($all_items as $item) {
			if (is_numeric($item['time'])) {
				$time = $item['time'];
			}
			else {
				$time = strtotime($item['time']);
			}
			$area_data[] = [
				$time * 1000,
				floatval($item['total'])
			];
		}

		return $area_data;
	}

	/**
	 * @param       $query
	 * @param       $field
	 * @param       $time_field
	 * @param       $year
	 * @param array $condition
	 * @return array
	 */
	public static function getSplineData($query, $field, $time_field, $year, $condition = []) {

		//symbols
		//default
		//https://github.com/highcharts/highcharts/tree/master/samples/graphics
		//custom
		//http://jsfiddle.net/gh/get/library/pure/highcharts/highcharts/tree/master/samples/highcharts/plotoptions/series-marker-symbol/

		//THIS CASE NO ALIAS
		//SELECT month_number, COALESCE(SUM(amount), 0) as total
		//FROM all_month
		//LEFT JOIN app_user_transaction ON month_number = FROM_UNIXTIME(time, '%m')
		//WHERE FROM_UNIXTIME(`time`, '%Y') = 2017 AND `status` = 'approved'
		//GROUP BY month_number
		//array_map('floatval', array_column($yearly_pending, 'total'))

		/*$yearly_approved = Yii::$app->db->createCommand("
			SELECT FROM_UNIXTIME(`time`, '%m') as month_number, COALESCE(SUM(amount), 0) as total
		FROM app_user_transaction
		WHERE FROM_UNIXTIME(`time`, '%Y') = $year AND `status` = 'approved'
		GROUP BY month_number
		")->queryAll();*/

		$current_year = date('Y', time());
		$year         = $year ? $year : $current_year;
		/* @var ActiveQuery $query */

		$input = $query->select(["sum($field) as total", "FROM_UNIXTIME(`$time_field`, '%m') as month_number"])->where($condition)
		               ->groupBy('month_number')//->groupBy(new \yii\db\Expression('FROM_UNIXTIME(`$time_field`, "%m")'))
		               ->asArray()->all();

		$months = Chart::getMonths();

		$i                = 0;
		$max              = 0;
		$max_amount_month = '';
		$output           = array();

		foreach ($input as $monthly_data) {
			$month = $monthly_data['month_number'];
			if ($monthly_data['total'] > $max) {
				$max              = $monthly_data['total'];
				$max_amount_month = $month;
			}
			$output[$month] = floatval($monthly_data['total']);
			$i++;
		}

		$output[$max_amount_month] = [
			'y'      => floatval($max),
			'marker' => [
				'symbol' => 'url(https://www.highcharts.com/samples/graphics/sun.png)'
			]
		];

		$check_hollow = array_diff($months, array_keys($output));
		if ($check_hollow != 0) {
			foreach ($check_hollow as $hollow) {
				$output[$hollow] = 0;
			}
		}
		ksort($output, SORT_NUMERIC);

		return array_values($output);
	}

	/**
	 * @return array
	 */
	public static function getMonths() {
		return ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
	}

	/**
	 * @param bool $year
	 * @return array
	 * @throws \Exception
	 */
	public static function getDates($year = false) {
		if (!$year) {
			$year = date('Y', time());
		}

		$begin = new DateTime('2013-02-01');
		$end   = new DateTime('2013-02-13');

		$date_range = new DatePeriod($begin, new DateInterval('P1D'), $end);

		$dates = array();
		/* @var $date DateTime */
		foreach ($date_range as $date) {
			$dates[] = $date->format("Y-m-d");
		}

		return $dates;
	}

	/**
	 * @param        $items
	 * @param        $options
	 * @param string $column_name
	 * @param string $column_total
	 * @return array
	 * @throws \Exception
	 */
	public static function get3dColumn($items, $options, $column_name = 'name', $column_total = "total") {
		$data = [];
		if (!is_array($items)) {
			return [];
		}
		$colors = array_column($options, 'color');
		foreach ($items as $key => $item) {
			$data[] = [
				'color' => isset($item['color']) ? $item['color'] : (isset($colors[$key]) ? $colors[$key] : self::getRandomColor()),
				'name'  => $item[$column_name],
				'data'  => is_array($item[$column_total]) ? $item[$column_total] : [(float) $item[$column_total]]
			];
		}

		return $data;
	}

	/**
	 * @param int $number
	 * @return array|string
	 * @throws \Exception
	 */
	public static function getRandomColor($number = 1) {
		$color_list = ['A', 'B', 'C', 'D', 'E', 'F', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		$c1         = random_int(0, 15);
		$c2         = random_int(0, 15);
		$c3         = random_int(0, 15);
		$c4         = random_int(0, 15);
		$c5         = random_int(0, 15);
		$c6         = random_int(0, 15);
		$colors     = [];
		for ($i = 0; $i < $number; $i++) {
			$color = $color_list[$c1] . $color_list[$c2] . $color_list[$c3] . $color_list[$c4] . $color_list[$c5] . $color_list[$c6];
			$color = '#' . $color;
			if ($number == 1) {
				return $color;
			}
			$colors[] = $color;
		}

		return $colors;
	}

	/**
	 * @param        $items
	 * @param        $options
	 * @param string $column_name
	 * @param string $column_total
	 * @return array
	 * @throws \Exception
	 */
	public static function getLineBasic($items, $options, $column_name = 'name', $column_total = "total") {
		$data = [];
		if (!is_array($items)) {
			return [];
		}
		$colors = array_column($options, 'color');
		foreach ($items as $key => $item) {
			$data[] = [
				'color' => isset($item['color']) ? $item['color'] : (isset($colors[$key]) ? $colors[$key] : self::getRandomColor()),
				'name'  => $item[$column_name],
				'data'  => is_array($item[$column_total]) ? $item[$column_total] : [(float) $item[$column_total]]
			];
		}

		return $data;
	}

	/**
	 * @param        $items
	 * @param string $field_name
	 * @param string $field_total
	 * @return array
	 */
	public static function getFullDayInMonth($items, $field_name = 'name', $field_total = 'total') {
		$data    = [];
		$arr_day = array_column($items, $field_name);
		for ($i = 1; $i <= self::getLastDay(); $i++) {
			if (in_array($i, $arr_day)) {
				$data[] = $items[array_search($i, $arr_day)];
			}
			else {
				$data[] = [
					$field_total => 0,
					$field_name  => $i
				];
			}
		}

		return $data;
	}

	/**
	 * @param string $day // 2018-04-18
	 * @return string
	 */
	public static function getLastDay($day = '') {
		if ($day == '') {
			$date = new DateTime('now');

		}
		else {
			$date = new DateTime($day);
		}
		$date->modify('last day of this month');

		return $date->format('d');
	}

	public static function getFullDate($dates) {
		$data      = [];
		$prev_date = -1;
		foreach ($dates as $index => $date) {
			if ($date - 1 == $prev_date) {
				$data[] = $date;
			}
			else {
				if ($prev_date == -1) {
					$data[] = $date;
				}
				else {
					for ($index = $prev_date + 1; $index <= $date; $index++) {
						$data[] = $index;
					}
				}
			}

			$prev_date = $date;
		}

		return $data;
	}
}

?>