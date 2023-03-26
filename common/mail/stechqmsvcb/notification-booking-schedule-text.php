<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 11/05/2018
 * Time: 11:01
 */


/** @var \applications\stechqmsvcb\models\EcommerceOrder $model */
if (!isset($model)) {
	// test
	//$model = EcommerceOrder::find()->where(['id' => 9])->one();
}
$url = Yii::$app->urlManager->hostInfo . Yii::$app->urlManager->baseUrl;
?>
<!-- SECTION 1 -->
<!--    <h3></h3>-->
<?php $url_notification = str_replace('/backend/web', '', $url . "/site/notification?type=new&status=SUCCESS&id=" . $model->id . "&ticketcode=" . $model->getTicketcode() . "&ticket_number=" . $model->getTicketNumber()); ?>
Chào <?= $model->billing_name ?>

<?php if ($time == '3h'): ?>
    Bạn có lịch hẹn tại Vietcombank vào lúc: <?= $model->order_time ?>
<?php elseif ($time == '30m'): ?>
    Vé của bạn sắp đến lượt. Vui lòng đến chi nhánh Vietcombank để thực hiện giao dịch.
<?php endif; ?>

<?= $url_notification ?> Chi tiết xem tại đây

