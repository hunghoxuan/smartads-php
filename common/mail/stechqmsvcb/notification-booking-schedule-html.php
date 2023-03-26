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
$url         = Yii::$app->urlManager->hostInfo . Yii::$app->urlManager->baseUrl;
?>
<div class="wizard" id="wizard">
    <!-- SECTION 1 -->
    <!--    <h3></h3>-->
    <section>
		<?php $url_notification = str_replace('/backend/web', '', $url . "/site/notification?type=new&status=SUCCESS&id=" . $model->id . "&ticketcode=" . $model->getTicketcode() . "&ticket_number=" . $model->getTicketNumber()); ?>
        <h3>Chào <b><?= $model->billing_name ?></b></h3>
        <br>
		<?php if ($time == '3h'): ?>
            <p>Bạn có lịch hẹn tại <b>Vietcombank</b> vào lúc: <?= $model->order_time ?></p>
		<?php elseif ($time == '30m'): ?>
            <p>Vé của bạn sắp đến lượt. Vui lòng đến chi nhánh của <b>Vietcombank</b> để thực hiện giao dịch.</p>
		<?php endif; ?>
        <br>
        <a style="text-decoration: none;cursor: pointer;font-size: 20px;" href="<?= $url_notification ?>">Chi tiết xem tại đây</a>
        <div class="clearfix"></div>
    </section>
</div>
