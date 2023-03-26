<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 09/05/2018
 * Time: 15:43
 */

namespace common\widgets\fchart\models;


/**
 * Class ChartLineColumn
 * @package common\widgets\fchart\models
 */
class ChartLineColumn extends Chart
{
	/**
	 * @var bool
	 */
	public $is_stack = false;

	/**
	 * @var bool|integer
	 */
	public $is_multiple_yAxis = false;

	/**
	 * ChartLineColumn constructor.
	 */
	public function __construct() {
		$this->type = self::LINE_COLUMN;
	}

	/**
	 * @return mixed
	 */
	public function getChart() {
		//$this->series =
		// TODO: Implement getChart() method.
	}

	/**
	 * @return bool
	 */
	public function isStack() {
		return $this->is_stack;
	}

	/**
	 * @param bool $is_stack
	 */
	public function setIsStack($is_stack) {
		$this->is_stack = $is_stack;
	}
}