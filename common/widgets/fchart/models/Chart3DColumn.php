<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/18/2018
 * Time: 9:58 AM
 */

namespace common\widgets\fchart\models;


/**
 * Class Chart3DColumn
 * @package common\widgets\fchart\models
 */
class Chart3DColumn extends Chart
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
     * Chart3DColumn constructor.
     */
    public function __construct()
    {
        $this->type = self::_3D_COLUMN;
    }

    /**
     * @throws \Exception
     */
    public function getChart() {
        $this->series = \common\widgets\fchart\Chart::get3dColumn($this->items, $this->options, $this->column_name, $this->column_total);
        return $this->series;
    }
}