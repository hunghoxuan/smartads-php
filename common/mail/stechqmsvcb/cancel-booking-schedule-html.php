<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 11/05/2018
 * Time: 11:01
 */

use backend\modules\ecommerce\models\EcommerceOrder;
use backend\modules\ecommerce\models\EcommerceOrderItem;
use backend\modules\qms\models\QmsBranch;
use backend\modules\qms\models\QmsServices;
use Da\QrCode\QrCode;
use common\components\FHtml;

/** @var EcommerceOrder $model */
if (!isset($model)) {
	// test
	//$model = EcommerceOrder::find()->where(['id' => 9])->one();
}

$url         = Yii::$app->urlManager->hostInfo . Yii::$app->urlManager->baseUrl;
$url_booking = str_replace('/backend/web', '', $url . "/site/booking-schedule");
?>
<?php
$baseUrl = \common\components\FHtml::currentFrontendBaseUrl();
$baseUrl .= "/assets/";
?>

<link rel="stylesheet" href="<?= assets_frontend('css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= assets_frontend('font-awesome/css/font-awesome.css') ?>">
<!-- MATERIAL DESIGN ICONIC FONT -->
<link rel="stylesheet" href="<?= assets_frontend('fonts/material-design-iconic-font/css/material-design-iconic-font.css') ?>">
<!-- STYLE CSS -->
<link rel="stylesheet" href="<?= assets_frontend('css/style.css') ?>">

<style type="text/css">
    table tr td:first-child {
        font-weight: bold;
    }
</style>
<div class="wizard" id="wizard">
    <!-- SECTION 1 -->
    <!--    <h3></h3>-->
    <section>

            <h3>Bạn đã hủy đăng ký vé tại Vietcombank: <b><?= $ticketcode?></b></h3>
            <div class="col-md-8 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td class="col-md-4">Họ tên</td>
                            <td><?= $model->billing_name ?></td>
                        </tr>
                        <tr>
                            <td>Số điện thoại</td>
                            <td><?= $model->billing_phone ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?= $model->billing_email ?></td>
                        </tr>
                        <tr>
                            <td>Thời gian</td>
                            <td><b><?= $model->order_time ?></b></td>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        <div class="col-md-12" style="text-align: center">
            <a href="<?= $url_booking ?>" class="btn btn-success">Trở lại trang chủ</a>
        </div>
        <div class="clearfix"></div>
    </section>
</div>
