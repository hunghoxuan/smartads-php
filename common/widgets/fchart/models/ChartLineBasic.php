<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/18/2018
 * Time: 2:14 PM
 */

namespace common\widgets\fchart\models;


/**
 * Class ChartLineBasic
 * @package common\widgets\fchart\models
 */
class ChartLineBasic extends Chart
{
	/**
	 * @var string
	 */
	public $column_name = 'name';
	/**
	 * @var string
	 */
	public $column_total = 'total';
	/**
	 * @var bool
	 */
	public $is_multiple = false;

	/**
	 * ChartLineBasic constructor.
	 */
	public function __construct() {
		$this->type = self::LINE_BASIC;
	}

	/**
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function getChart() {
		// TODO: Implement getChart() method.
		$this->series = \common\widgets\fchart\Chart::getLineBasic($this->items, $this->options, $this->column_name, $this->column_total);

		return $this->series;
	}
}