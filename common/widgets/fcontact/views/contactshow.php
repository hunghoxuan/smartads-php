<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 14/07/2017
 * Time: 16:22 CH
 */
use common\components\FHtml;
use backend\modules\cms\models\CmsContact;

$show_border = isset($show_border) ? $show_border : false;
$background_css = isset($background_css) ? $background_css : 'white';
$skype = isset($skype) ? $skype : FHtml::settingCompanyChat(false);
$email = isset($email) ? $email : FHtml::settingCompanyEmail(false);
$phone = isset($phone) ? $phone : FHtml::settingCompanyPhone(false);
$whatsapp = isset($whatsapp) ? $whatsapp : FHtml::settingCompanyWhatsapp(false);
$address = isset($address) ? $address : FHtml::settingCompanyAddress();
$facebook = isset($facebook) ? $facebook : FHtml::settingCompanyFacebook();
$title = isset($title) ? $title : '';

// border: 1px solid #ddd
?>
<style type="text/css">
    .contact-info {
        border: <?php if($show_border) echo 1; else echo 0; ?>px solid #ddd;
        /*padding-left: 30px; */
        <?= $background_css ?>;
       margin: 10px !important;
    }

    .contact-info p a {
        font-size: 14pt !important;
        font-weight: bold;
        margin-top: -5px;
    }

    .info-single {
        border-bottom: 1px dashed lightgray;
        padding-top: 10px;
        margin-bottom: 2px;
        background-color: white;

    }

    .info-single i {
        border-radius: 50%;
        float: left;
        font-size: 20px;
        height: 40px;
        line-height: 38px;
        margin-bottom: 10px;
        margin-right: 15px;
        text-align: center;
        width: 40px;
    }
</style>
<?php if ($show_border) {
    echo "<div style='padding:20px; border: 1px dashed lightgray'>";
} ?>
    <div class="panel-body" style="padding-left: 20px !important; font-size: 14pt">
        <table class="" style="width: 100%">

            <tr class="row info-single">
                <td class="col-xs-2 no-padding">
                    <i class="fa fa-skype " aria-hidden="true" style="color: royalblue;border: 1px solid #00AFF0;"></i>
                </td>
                <td class="col-xs-10 no-padding">
                    <p style="margin-top:10px;color: #00AFF0"><a style="display: inline-block; color: royalblue;"  href="skype:<?= $skype ?>?chat"  target="_blank" alt="Skype" ><?= $skype ?></a> </p>
                </td>
            </tr>

            <tr class="row info-single">
                <td class="col-xs-2">
                    <i class="fa fa-phone" aria-hidden="true" style="color: forestgreen;border: 1px solid #43B51F;"></i>
                </td>
                <td class="col-xs-10 no-padding">
                    <p style="margin-top:10px;color: #43B51F;">
                        <a style="display: inline-block; color: forestgreen;"  target="_blank" alt="Call me" href="tel:<?= $phone ?>">
                            <?= $phone ?>
                        </a>
                    </p>
                </td>

            </tr>

            <tr class="row info-single">
                <td class="col-xs-2">
                    <i class="fa fa-whatsapp" aria-hidden="true" style="color: forestgreen;border: 1px solid #43B51F;"></i>
                </td>
                <td class="col-xs-10 no-padding">
                    <p style="margin-top:10px;color: #e09e25">
                        <a style="display: inline-block; color: forestgreen;" target="_blank" alt="Whatsapp" href="<?= FHtml::getWhatsappChatLink($phone) ?>">
                            <?= $whatsapp ?>
                        </a>
                    </p>
                </td>

            </tr>
            <tr class="row info-single">
                <td class="col-xs-2">
                    <i class="fa fa-facebook" aria-hidden="true" style="color: royalblue;border: 1px solid royalblue;"></i>
                </td>
                <td class="col-xs-10 no-padding">
                    <p style="margin-top:10px;color: #e09e25">
                        <a style="display: inline-block; color: royalblue;" target="_blank" alt="Facebook" href="<?= FHtml::getFacebookChatLink($facebook) ?>">
                            <?= $facebook ?>
                        </a>
                    </p>
                </td>

            </tr>
            <tr class="row info-single">
                <td class="col-xs-2">
                    <i class="fa fa-envelope" aria-hidden="true" style="color: darkorange;border: 1px solid #43B51F;"></i>
                </td>
                <td class="col-xs-10 no-padding">
                    <p style="margin-top:10px;color: #e09e25"><a  target="_blank" alt="Email" style="display: inline-block; color: darkorange;" href="mailto:<?= $email ?>"><?= $email ?></a>
                    </p>
                </td>

            </tr>


            <tr class="row info-single">
                <td class="col-xs-2">
                    <i class="fa fa-envelope" aria-hidden="true" style="color: darkorange;border: 1px solid #43B51F;"></i>
                </td>
                <td class="col-xs-10 no-padding">
                    <p style="margin-top:10px;color: #e09e25"><a style="display: inline-block; color: darkorange;"  href="#"><?= $address ?></a>
                    </p>
                </td>
            </tr>

            <tr class="row info-single">
                <td colspan="2" class="col-md-12">
                    <?php echo FHtml::showGoogleMap($address) ?>

                </td>
            </tr>

        </table>
    </div>
<?php if ($show_border) {
    echo '</div>';
} ?>

