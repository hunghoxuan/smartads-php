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
if (!isset($model)) {
	// test
	//$model = EcommerceOrder::find()->where(['id' => 9])->one();
}

/** @var EcommerceOrder $model */
?>
<?php
if (isset($model)):
	$qrCode = (new QrCode($ticketcode . "#"));

	// display directly to the browser
	header('Content-Type: ' . $qrCode->getContentType());
	//echo $qrCode->writeString();
	/** @var EcommerceOrderItem $item */
	$item = $model->item;
	/** @var QmsServices $service */
	$service = QmsServices::find()->where(['id' => $item->object_id])->one();
	/** @var QmsBranch $branch */
	$branch = QmsBranch::find()->where(['id' => $model->branch_id])->one();
	?>
    Thông tin vé

    Họ tên
	<?= $model->billing_name ?>

    Số điện thoại
	<?= $model->billing_phone ?>

    Email
	<?= $model->billing_email ?>

    Dịch vụ
	<?= isset($service) ? $service->name  : ''?>

    Chi nhánh
	<?= isset($branch) ? $branch->name  : ''?>

    SĐT Chi nhánh
	<?= isset($branch) ? $branch->telephone  : ''?>

    Từ địa chỉ
	<?= $model->billing_address ?>

    Đến điạ chỉ
    <?= isset($branch) ? $branch->address  : ''?>

    <?= $ticketcode; ?>

    Mã dùng để kiểm tra tại quầy giao dịch

<?php else: ?>

    Không tìm thấy thông tin

<?php endif; ?>

<?= \common\components\FHtml::createUrl('site/booking-schedule') ?> Trở lại trang chủ
