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
                <th>
                    Loại vàng
                </th>
                <th>
                    Mua
                </th>
                <th>
                    Bán
                </th>
            </tr>

            <tr>
                <td style="font-weight:bold;">
                    SJC (5c)
                </td>
                <td>
                    3.635.000
                </td>
                <td>
                    3.645.000
                </td>
            </tr>

            <tr class="alter">
                <td style="font-weight:bold;">
                    SJC (1L)
                </td>
                <td>
                    3.635.000
                </td>
                <td>
                    3.645.000
                </td>
            </tr>

            <tr>
                <td style="font-weight:bold;">
                    SJC (10L)
                </td>
                <td>
                    3.635.000
                </td>
                <td>
                    3.645.000
                </td>
            </tr>

            </tbody>
        </table>



