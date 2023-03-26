<?php

namespace backend\actions;

require(__DIR__ . '/../../printer/vendor/autoload.php');

use backend\models\ObjectCategoryAPI;
use backend\models\Setting;
use common\components\FConstant;
use common\components\FHtml;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use yii\helpers\Json;

class PrintAction extends BaseAction
{
    public function run()
    {
        $session_data = FHtml::getRequestParam('session_data', '');
        $table = FHtml::getRequestParam('table', '');
        $time = FHtml::getRequestParam('time', '');
        $order_id = FHtml::getRequestParam('order_id', '');

        //kitchen print layout
        $after_table = str_repeat(" ", 6 - strlen($table));
        $begin_start = "--------------------------------\n";
        $begin = $begin_start . "TABLE #$table".$after_table."$time\n";
        $begin .=  "ORDER # $order_id\n";
        $begin_end = "--------------------------------\n\n";
        $begin .= $begin_end;

        $end = "------------- END --------------\n";

        //cashier_print_layout
		/*
        $after_cashier_table = str_repeat("-", 21 - strlen($table));
        $begin_cashier_start = "--------------------TABLE #$table" . "$after_cashier_table\n";
        $begin_cashier = $begin_cashier_start . "FOOD LIST              | $time\n";
        $begin_cashier_end = "------------------------------------------------\n";
        $begin_cashier .= $begin_cashier_end;
		$end_cashier = "----------------------- END --------------------\n";
        */
        $c = array();
        $print_array = array();
        $full_content = "";

        /* @var ObjectCategoryAPI $category */

        //$categories = ObjectCategoryAPI::find()->all();
        //foreach ($categories as $category) {
        //    $c[$category->id] = $category->printer_ip;
        //}
        
        $session_data_array = Json::decode($session_data);
        if(is_array($session_data_array)){
            foreach ($session_data_array as $item) {
                $content = "";
                $quantity = $item['quantity'];
                $food_string = $quantity . " x " . strtoupper($item['product_name']);
                $reserve_food_string = strtoupper($item['product_name']) . " x " . $quantity;
                $note_string = $item['note'];

                $content .= $food_string . "\n";
                if (strlen($note_string) != 0) {
                    $content .= $note_string . "\n";
                }

                $content .= "\n";


                //$ip = $c[$item['category_id']];
                $ip = $item['printer_ip'];

                if (!isset($print_array[$ip])) {
                    $print_array[$ip] = $content;
                } else {
                    $print_array[$ip] .= $content;
                }

                //$full_content .= $reserve_food_string."\n";
				$full_content .= $content;
            }

            foreach ($print_array as $key => $value) {

                $string = $begin . $value . $end;

                $connector = new NetworkPrintConnector($key, 9100);
                $printer = new Printer($connector);
				$printer->setTextSize(2, 2);
                $printer->setFont(Printer::FONT_B);
                $printer->text($string);
                $printer->feed(2);
                $printer->cut();
                $printer->close();
            }

            $cashier_printer_ip = Setting::getSettingValueByKey(FConstant::CASHIER_PRINTER_IP);

            //$cashier_string = $begin_cashier . $full_content . $end_cashier;
			$cashier_string = $begin . $full_content . $end;

            $connector = new NetworkPrintConnector($cashier_printer_ip, 9100);
            $printer = new Printer($connector);
			//small
			//$printer->feedReverse(1);
			//$printer->selectPrintMode(Printer::MODE_EMPHASIZED);
			//big
			$printer->setTextSize(2, 2);
            $printer->setFont(Printer::FONT_B);
            $printer->text($cashier_string);
            $printer->feed(2);
            $printer->cut();
            $printer->close();
        }
    }
}
