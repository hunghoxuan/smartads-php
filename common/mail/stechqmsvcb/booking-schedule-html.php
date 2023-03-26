<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 11/05/2018
 * Time: 11:01
 */

use applications\stechqmsvcb\backend\modules\ecommerce\models\EcommerceOrderItem;
use backend\modules\qms\models\QmsBranch;
use backend\modules\qms\models\QmsServices;
use Da\QrCode\QrCode;
use common\components\FHtml;

/** @var \applications\stechqmsvcb\models\EcommerceOrder $model */
if (!isset($model)) {
	// test
	//$model = EcommerceOrder::find()->where(['id' => 9])->one();
}
$url              = Yii::$app->urlManager->hostInfo . Yii::$app->urlManager->baseUrl;
$url_booking      = str_replace('/backend/web', '', $url . "/site/booking-schedule");
$url_booking_list = str_replace('/backend/web', '', $url . "/site/booked-schedule-list");
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

		<?php
		if (isset($model)):

			/** @var EcommerceOrderItem $item */
			$filename = $ticketcode . $model->id . '.png';

			$item = $model->item;
			/** @var QmsServices $service */
			$service = QmsServices::find()->where(['id' => $item->object_id])->one();
			/** @var QmsBranch $branch */
			$branch           = QmsBranch::find()->where(['id' => $model->branch_id])->one();
			$url_notification = str_replace('/backend/web', '', $url . "/site/notification?type=new&status=SUCCESS&id=" . $model->id . "&ticketcode=" . $model->getTicketcode() . "&ticket_number=" . $model->getTicketNumber());
			?>
            <h3>Thông tin đặt chỗ</h3>
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
                            <td>Dịch vụ</td>
                            <td><?= isset($service) ? $service->name : '' ?></td>
                        </tr>
                        <tr>
                            <td>Chi nhánh</td>
                            <td><?= $branch->name ?></td>
                        </tr>
                        <tr>
                            <td>SĐT Chi nhánh</td>
                            <td><?= isset($branch) ? $branch->telephone : '' ?></td>
                        </tr>
                        <tr>
                            <td>Thời gian</td>
                            <td><b><?= $model->order_time ?></td>
                        </tr>
                        <tr>
                            <td>So ve</td>
                            <td><big><b><i><?= $model->showTickerNumber() ?></i></b></big></td>
                        </tr>
                        <tr>
                            <td>Từ địa chỉ</td>
                            <td><?= $model->billing_address ?></td>
                        </tr>
                        <tr>
                            <td>Đến điạ chỉ</td>
                            <td><?= isset($branch) ? $branch->address : '' ?></td>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-md-4 col-xs-12" style="text-align: center">
                <img src="<?= FHtml::getFileUrl($filename, 'qrcode'); ?>" alt="">
                <br>
                <span style="font-size: 30px;color: #000;text-align: center">[&nbsp;&nbsp;<?= $ticketcode; ?>&nbsp;&nbsp;]</span>
                <br>
                <span style="font-size: 25px;color: #000;text-align: center">Mã dùng để kiểm tra tại quầy giao dịch</span>
            </div>
            <div class="col-md-12" style="text-align: center">
                <a href="<?= $url_notification ?>" class="btn btn-success">Kiểm tra vé</a>
            </div>
            <div class="col-md-12" style="text-align: center">
                <a class="hidden-print" href="<?= Yii::$app->urlManager->hostInfo . \common\components\FHtml::createUrl('qms/api/download-qrcode', [
					'id'         => $model->id,
					'ticketcode' => $ticketcode
				]) ?>" style="font-size: 13px;cursor:pointer;text-align: center">Tải QR Code</a>
            </div>
            <div class="col-md-12">
                <a class="btn btn-success btn-xs" href="<?= $url_booking_list . "?" . http_build_query(['code' => $model->billing_email]) ?>">Danh sách vé đã đặt</a>
            </div>
		<?php else: ?>
            <div class="col-md-12" style="text-align: center">
                <h3>Không tìm thấy thông tin</h3>
            </div>
		<?php endif; ?>

        <div class="col-md-12" style="text-align: center">
            <a href="<?= $url_booking ?>" class="btn btn-success">Trở lại trang chủ</a>
        </div>
        <div class="clearfix"></div>
    </section>
</div>
