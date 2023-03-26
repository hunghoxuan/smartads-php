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
            <tbody><tr>
                <td class="laisuat_content_td" colspan="3" width="100%">
                    <label class="style_font">
                        TP Hà Nội</label>
                </td>
            </tr>
            <tr>
                <td class="laisuat_content_td" width="35%" rowspan="2" style="vertical-align:middle;">
                    <label class="style_font">
                        Kỳ hạn</label>
                </td>
                <td class="row1" colspan="2" width="65%">
                    <label class="style_font">
                        Loại tiền gửi (%năm)</label>
                </td>
            </tr>

            <tr class="style_font">


                <td class="row2">
                    USD
                </td>

                <td class="row2">
                    VND
                </td>


            </tr>

            <!-- ===================================== -->

            <tr class="laisuat_content1">

                <td class="row1">
                    5 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    4,8%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content2">

                <td class="row1">
                    KKH
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    0,2%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content1">

                <td class="row1">
                    1 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    4,1%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content2">

                <td class="row1">
                    2 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    4,1%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content1">

                <td class="row1">
                    3 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    4,8%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content2">

                <td class="row1">
                    6 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    5,3%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content1">

                <td class="row1">
                    9 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    5,5%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content2">

                <td class="row1">
                    364 ngày
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    6,7%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content1">

                <td class="row1">
                    12 tháng(*)
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    6,9%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content2">

                <td class="row1">
                    13 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    6,8%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content1">

                <td class="row1">
                    18 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    6,9%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content2">

                <td class="row1">
                    24 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    6,9%&nbsp;
                </td>


            </tr>

            <tr class="laisuat_content1">

                <td class="row1">
                    36 tháng
                </td>


                <td class="row1">
                    &nbsp;-&nbsp;
                </td>

                <td class="row1">
                    6,9%&nbsp;
                </td>


            </tr>

            <tr>
                <td style="height: 4px" class="br6" colspan="3">
                </td>
            </tr>
            </tbody>
        </table>



