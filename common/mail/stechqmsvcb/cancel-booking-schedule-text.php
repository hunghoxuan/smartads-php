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

/** @var EcommerceOrder $model */
?>
Bạn đã hủy vé : <?= $ticketcode; ?> tại chi nhánh  <?= isset($branch) ? $branch->name : '' ?> của Vietcombank

<?= \common\components\FHtml::createUrl('site/booking-schedule') ?> Trở lại trang chủ
