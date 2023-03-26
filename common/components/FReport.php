<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use creocoder\flysystem\FtpFilesystem;
use League\Flysystem\Filesystem;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use Globals;
use common\components\Setting;
use yii\helpers\StringHelper;
use yii\helpers\Url;


class FReport extends FConstant
{
    /**
     * @param string $timeType
     * @param        $startDate
     * @param        $endDate
     * @param self   $model
     * @return bool
     * tinh toan thoi gian bao cao
     */
    public static function getTimeRange($timeType, &$startDate, &$endDate, &$model = null) {
        switch ($timeType) {
            case '': //tat ca cac ngay
                $model->start_date = "";
                $model->end_date   = "";

                return false;
            //                $date = date("Y-m-d H:i:s");
            //                $startDate = strtotime(date("2010-01-01"));
            //                $endDate = strtotime($date);
            //                break;

            case 'd1': //hom nay
                $date      = getdate();
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $endDate   = time();
                break;

            case 'd2': //hom qua
                $date      = getdate(strtotime('-1 day'));
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $date      = getdate();
                $endDate   = time();
                $endDate   = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                //echo $endDate;die;
                break;

            case 'd3': //7 ngay qua
                $date      = getdate(strtotime('-6 day'));
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $endDate   = time();
                break;

            case 'w1': //tuan nay

                //ngay can tru
                $n = (date('w') == 0) ? 6 : date('w');

                $date      = getdate(strtotime('-' . $n . ' day'));
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $endDate   = time();
                break;

            case 'w2': //tuan truoc

                //ngay can tru
                $n       = (date('w') == 0) ? 6 : date('w');
                $n_start = $n + 7;

                $date      = getdate(strtotime('-' . $n_start . ' day'));
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $date      = getdate(strtotime('-' . $n . ' day'));
                $endDate   = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                //$endDate   = time();
                break;

            case 'w3': //4 tuan truoc

                //ngay can tru
                $n = (date('w') == 0) ? 6 : date('w');
                $n += 21;

                $date      = getdate(strtotime('-' . $n . ' day'));
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $endDate   = time();
                break;

            case 'm1': //thang nay

                //ngay can tru
                $n = date('j');

                $date      = getdate(strtotime('-' . ($n - 1) . ' day'));
                $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
                $endDate   = time();
                break;

            case 'm2': //thang truoc

                $date      = getdate();
                $startDate = mktime(0, 0, 0, $date['mon'] - 1, 1, $date['year']);
                $endDate   = time();
                $endDate   = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
                break;

            case 'm3': //3 thang truoc

                $date      = getdate();
                $startDate = mktime(0, 0, 0, $date['mon'] - 3, 1, $date['year']);
                $endDate   = time();
                break;

            case 'q1': //quy 1

                $date      = getdate();
                $startDate = mktime(0, 0, 0, 1, 1, $date['year']);
                $endDate   = mktime(0, 0, 0, 4, 1, $date['year']);
                break;
            case 'q2': //quy 2

                $date      = getdate();
                $startDate = mktime(0, 0, 0, 4, 1, $date['year']);
                $endDate   = mktime(0, 0, 0, 7, 1, $date['year']);
                break;
            case 'q3': //quy 3

                $date      = getdate();
                $startDate = mktime(0, 0, 0, 7, 1, $date['year']);
                $endDate   = mktime(0, 0, 0, 10, 1, $date['year']);
                break;
            case 'q4': //quy 4

                $date      = getdate();
                $startDate = mktime(0, 0, 0, 10, 1, $date['year']);
                $endDate   = mktime(0, 0, 0, 1, 1, $date['year'] + 1);
                break;

            case 'y1': //nam nay

                $date      = getdate();
                $startDate = mktime(0, 0, 0, 1, 1, $date['year']);
                $endDate   = mktime(0, 0, 0, 1, 1, $date['year'] + 1);
                break;
            case 'y2': //nam truoc

                $date      = getdate();
                $startDate = mktime(0, 0, 0, 1, 1, $date['year'] - 1);
                $endDate   = mktime(0, 0, 0, 1, 1, $date['year']);
                break;

            case 'range': //theo range
                $start_date = isset($_POST["start_date"]) ? $_POST["start_date"] : $model->start_date;
                $end_date   = isset($_POST["end_date"]) ? $_POST["end_date"] : $model->end_date;
                $startDate  = strtotime(date($start_date));
                $endDate    = strtotime(date($end_date));
                if ($startDate > $endDate) {
                    $a         = $startDate;
                    $startDate = $endDate;
                    $endDate   = $a;
                }
                if (strtotime($end_date) == strtotime(date("Y-m-d"))) {
                    $end_date = time();
                }

                $endDate = $end_date;

                setcookie("start_date", $start_date);
                setcookie("end_date", $end_date);
                break;

            default:
                $model->start_date = "";
                $model->end_date   = "";

                return false;
        }
        if ($startDate > $endDate) {
            $a         = $startDate;
            $startDate = $endDate;
            $endDate   = $a;
        }

        $model->start_date = date("Y-m-d", $startDate);
        $model->end_date   = date("Y-m-d", $endDate);

        return true;
    }

    /**
     * @param bool $get_all_day
     * @return array
     */
    public static function getTimeRangeArray($get_all_day = true) {
        $day_list = array(
            'd1'    => FHtml::t('common', 'today'),
            'd2'    => FHtml::t('common', 'yesterday'),
            'd3'    => FHtml::t('common', '7 days ago'),
            'w1'    => FHtml::t('common', 'this week'),
            'w2'    => FHtml::t('common', 'last week'),
            'w3'    => FHtml::t('common', '7 weeks ago'),
            'm1'    => FHtml::t('common', 'this month'),
            'm2'    => FHtml::t('common', 'last month'),
            'm3'    => FHtml::t('common', '3 months ago'),
            'q1'    => FHtml::t('common', 'first quarter'),
            'q2'    => FHtml::t('common', 'second quarter'),
            'q3'    => FHtml::t('common', 'third quarter'),
            'q4'    => FHtml::t('common', 'four quarter'),
            'y1'    => FHtml::t('common', 'This Year'),
            'y2'    => FHtml::t('common', 'Last Year'),
            'range' => FHtml::t('common', 'range time'),
        );
        if ($get_all_day) {
            $day_list = array_merge(['' => FHtml::t('common', 'all')], $day_list);
        }

        return $day_list;
    }


}