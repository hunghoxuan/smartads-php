<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/18/2018
 * Time: 2:02 PM
 */

namespace common\widgets\fchart\models;

use common\components\FHtml;
use yii\db\ActiveQuery;


/**
 * Class ChartPie
 * @package common\widgets\fchart\models
 */
class ChartPie extends Chart
{
    /**
     * @var
     */
    public $field;
    /**
     * @var string
     */
    public $field_total = 'total';
    /**
     * @var
     */
    public $time_field;
    /**
     * @var string
     */
    public $filter_type = '';
    /**
     * @var bool
     */
    public $year = false;
    /**
     * @var bool
     */
    public $month = false;
    /**
     * @var bool
     */
    public $date = false;
    /**
     * @var string
     */
    public $important = '';
    /**
     * @var ActiveQuery | array
     */
    public $items = [];

    /**
     * @var array
     */
    public $data = [];
    /**
     * @var string
     */
    public $name = '';

    /**
     * ChartPie constructor.
     */
    public function __construct()
    {
        $this->scripts = [
            'modules/exporting',
            'themes/grid-light',
        ];
        $this->type = self::PIE;
    }

    const   FILTER_TYPE_YEARLY = 'yearly',
        FILTER_TYPE_MONTHLY = 'monthly',
        FILTER_TYPE_DAILY = 'daily';

    /**
     * @return array|mixed
     */
    public function getChart()
    {
        $this->data = \common\widgets\fchart\Chart::getPieData($this->items, $this->options, $this->field, $this->field_total, $this->time_field, $this->filter_type, $this->year, $this->month, $this->date, $this->important, $this->is_percentage);
        // TODO: Implement getChart() method.
        $this->series = [[
            'name' => $this->name,
            'data' => $this->data
        ]];
        return $this->series;
    }
}