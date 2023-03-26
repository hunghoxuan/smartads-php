<?php

namespace common\widgets\fchart\models;

use yii\base\Component;
use yii\helpers\BaseInflector;

/**
 * Class Chart
 * @property bool is_demo
 * @package common\widgets\fchart\models
 */
abstract class Chart
{
	/**
	 * @var array
	 */
	public $items = [];
	/**
	 * @var array
	 */
	public $options = [];
	/**
	 * @var string
	 */
	public $type = '';
	/**
	 * @var string
	 */
	public $portletTitle = '';
	/**
	 * @var string
	 */
	public $total_item = 6;
	/**
	 * @var int
	 */
	public $is_active = 1;

	/**
	 * @var array
	 */
	public $scripts = [
		'modules/drilldown',
		'modules/exporting',
		//        'themes/sand-signika',
	];

	/**
	 * @var array
	 */
	public $xAxis = [];
	/**
	 * @var array
	 */
	public $yAxis = [];
	/**
	 * @var array
	 */
	public $series = [];
	/**
	 * @var bool
	 */
	public $is_3d = false;
	/**
	 * @var array
	 */
	public $category = [];
	/**
	 * @var bool
	 */
	public $is_percentage = false;
	/**
	 * @var int
	 */
	public $size = 200;

	/**
	 * @var int
	 */
	public $height = 400;

	/**
	 * @var bool
	 */
	public $enableLabel = true;
	/**
	 * @var boolean
	 */
	public $is_demo = false;
	/**
	 * @var string
	 */
	public $title = '';
	const _3D_COLUMN  = '3d_column';
	const AREA        = 'area';
	const LINE_BASIC  = 'line_basic';
	const PIE         = 'pie';
	const SPLINE      = 'spline';
	const LINE_COLUMN = 'line_column';

	public function __construct() {
	}

	/**
	 * @return mixed
	 */
	abstract public function getChart();
}