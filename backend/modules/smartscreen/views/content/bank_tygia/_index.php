<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\widgets\FGridView;

use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;
use backend\modules\smartscreen\Smartscreen;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SmartscreenContent';
$moduleTitle = 'Smartscreen Content';
$moduleKey = 'smartscreen-queue';
$object_type = 'smartscreen_queue';

$this->title = FHtml::t($moduleTitle);


CrudAsset::register($this);

$models = isset($models) ? $models : FHtml::getModels('smartscreen_queue', '');
$models_limit = isset($limit) ? $limit : 5;
$models_count = count($models);
$form_type = FHtml::getRequestParam('form_type');
$null_value = isset($null_value) ? $null_value : Smartscreen::EMPTY_TEXT;

$footer_title = isset($footer_title) ? $footer_title : FHtml::getRequestParam('footer', 'description');
$header_title = isset($header_title) ? $header_title : FHtml::getRequestParam('header', 'title');

?>
        <table class="table">
            <tbody><tr style="height:20px;">
                <th>
                    Ngoại tệ
                </th>
                <th>
                    Mua
                </th>
                <th> Bán

                </th>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    USD
                </td>
                <td class="tygia_mua">
                    22.770,00
                </td>
                <td class="tygia_mua">
                    22.840,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    EUR
                </td>
                <td class="tygia_mua">
                    27.981,00
                </td>
                <td class="tygia_mua">
                    28.231,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    GBP
                </td>
                <td class="tygia_mua">
                    31.959,00
                </td>
                <td class="tygia_mua">
                    32.244,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    HKD
                </td>
                <td class="tygia_mua">
                    2.878,00
                </td>
                <td class="tygia_mua">
                    2.934,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    CHF
                </td>
                <td class="tygia_mua">
                    23.754,00
                </td>
                <td class="tygia_mua">
                    23.956,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    JPY
                </td>
                <td class="tygia_mua">
                    213,00
                </td>
                <td class="tygia_mua">
                    214,90
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    AUD
                </td>
                <td class="tygia_mua">
                    17.430,00
                </td>
                <td class="tygia_mua">
                    17.574,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    CAD
                </td>
                <td class="tygia_mua">
                    17.582,00
                </td>
                <td class="tygia_mua">
                    17.748,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    SGD
                </td>
                <td class="tygia_mua">
                    17.303,00
                </td>
                <td class="tygia_mua">
                    17.464,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    SEK
                </td>
                <td class="tygia_mua">
                    2.701,00
                </td>
                <td class="tygia_mua">
                    2.766,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    LAK
                </td>
                <td class="tygia_mua">
                    02,45
                </td>
                <td class="tygia_mua">
                    02,80
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    DKK
                </td>
                <td class="tygia_mua">
                    3.728,00
                </td>
                <td class="tygia_mua">
                    3.815,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    NOK
                </td>
                <td class="tygia_mua">
                    2.869,00
                </td>
                <td class="tygia_mua">
                    2.937,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    CNY
                </td>
                <td class="tygia_mua">
                    3.583,00
                </td>
                <td class="tygia_mua">
                    3.668,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    THB
                </td>
                <td class="tygia_mua">
                    700,84
                </td>
                <td class="tygia_mua">
                    758,12
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    RUB
                </td>
                <td class="tygia_mua">
                    359,00
                </td>
                <td class="tygia_mua">
                    440,00
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    NZD
                </td>
                <td class="tygia_mua">
                    16.304,00
                </td>
                <td class="tygia_mua">
                    16.544,00
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    VNĐ
                </td>
                <td class="tygia_mua">
                    -
                </td>
                <td class="tygia_mua">
                    -
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    USD (5-20)
                </td>
                <td class="tygia_mua">
                    -
                </td>
                <td class="tygia_mua">
                    -
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    USD (1-2)
                </td>
                <td class="tygia_mua">
                    -
                </td>
                <td class="tygia_mua">
                    -
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    KRW
                </td>
                <td class="tygia_mua">
                    -
                </td>
                <td class="tygia_mua">
                    23,29
                </td>
            </tr>

            <tr>
                <td class="style_font">
                    MYR
                </td>
                <td class="tygia_mua">
                    -
                </td>
                <td class="tygia_mua">
                    6.025,37
                </td>
            </tr>

            <tr class=" alter">
                <td class="style_font">
                    TWD
                </td>
                <td class="tygia_mua">
                    -
                </td>
                <td class="tygia_mua">
                    799,85
                </td>
            </tr>

            </tbody>
        </table>



