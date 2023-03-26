<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/18/2018
 * Time: 2:02 PM
 */

namespace common\widgets\fchart\models;


use yii\db\ActiveQuery;

class ChartArea extends Chart
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
    public $condition = '';
    /**
     * ChartPie constructor.
     */

    /**
     * ChartArea constructor.
     */
    public function __construct()
    {
        $this->type = self::AREA;
    }
    /**
     * @var ActiveQuery
     */
    public $query;
    /**
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function getChart()
    {
        // TODO: Implement getChart() method.
        $this->series = \common\widgets\fchart\Chart::getAreaData($this->query, $this->field, $this->time_field, $this->filter_type, $this->year, $this->month, $this->date, $this->condition);
        return $this->series;
    }
}