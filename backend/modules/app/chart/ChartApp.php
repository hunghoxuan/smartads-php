<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/19/2018
 * Time: 4:09 PM
 */

namespace backend\modules\app\chart;


use backend\modules\app\models\AppUserTransaction;
use common\components\FHtml;
use common\widgets\fchart\Chart;
use common\widgets\fchart\models\ChartLineBasic;

class ChartApp
{
    public static function showTransactionInMonth($month = 0) {
        if ($month == 0) {
            $month = date('m');
            $month_name = date('F');
        }
        $transactions = AppUserTransaction::find()->select(["count(`time`) as total", "FROM_UNIXTIME(`time`, '%d') as day"])->where("FROM_UNIXTIME(`time`, '%m')  =  $month")->groupBy('day')->asArray()->all();
        $chart = new ChartLineBasic();
        $chart->items = $transactions;
        $chart->column_name = FHtml::t('common', 'day');
        $chart->is_multiple_line = false;
        $chart->total_item = 12;
        $chart->getFullDayInMonth();
        $chart->getCategories();
        $chart->title =  FHtml::t('common', $month_name);
        $chart->yAxis = [
            'title' => ['text' => FHtml::t('common', 'Transaction')],
        ];
        $chart->getChart();
        return Chart::widget(['data' => $chart, 'columns' => 12, 'title' =>  FHtml::t('common', "Transaction statistic")]);
    }
}