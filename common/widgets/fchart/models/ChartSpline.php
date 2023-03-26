<?php
namespace common\widgets\fchart\models;

use yii\db\ActiveQuery;


/**
 * Class ChartSpline
 * @package common\widgets\fchart\models
 */
class ChartSpline extends Chart
{
    /**
     * @var string
     */
    public $field;
    /**
     * @var string
     */
    public $time_field;
    /**
     * @var int
     */
    public $year;
    /**
     * @var string
     */
    public $condition = '';
    /**
     * ChartSpline constructor.
     */
    public function __construct()
    {
        $this->type = self::SPLINE;
    }

    /**
     * @var ActiveQuery
     */
    public $query;
    /**
     * @return array|mixed
     */
    public function getChart()
    {
        // TODO: Implement getChart() method.
        $this->series = \common\widgets\fchart\Chart::getSplineData($this->query, $this->field, $this->time_field, $this->year, $this->condition);
        return $this->series;
    }
}