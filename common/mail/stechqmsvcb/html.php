<?php
use yii\helpers\Html;
use common\components\FHtml;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<div style="background-color: #ebeff2; width:90%; padding: 50px">
	<div style="background-color: #fafbfc; padding: 30px; text-align:center; border-bottom: 1px solid lightgray">
		<?= FHtml::showCurrentLogo('', '100px') ?>
	</div>
	<div style="background-color: white; padding: 80px">
		<?= $content ?>
	</div>
	<div style="background-color: #fff; padding: 50px; paddint-top:30px; padding-bottom: 30px; border-top: 1px solid lightgray; color:darkgrey">
		<table width="100%" border="0" cellpadding="10">
			<tr>
				<td style="width: 40%; line-height:150%">
					<?= FHtml::showCurrentLogo('', '50px') ?> <br/>

				</td>
				<td style="width: 30%; line-height:150%">
					<?= FHtml::settingCompanyWebsite(true) ?>. <br/>
					<?= FHtml::settingCompanyEmail(true) ?> <br/>
					<?= FHtml::settingCompanyPhone(true) ?> <br/>
					<?= FHtml::settingCompanyChat(true) ?>
				</td>
				<td style="width: 30%; line-height:150%">
					<?= FHtml::settingCompanyFacebook(true) ?>. <br/>
					<?= FHtml::settingCompanyYoutube(true) ?> <br/>
					<?= FHtml::settingCompanyTwitter(true) ?>
				</td>
			</tr>
		</table>

	</div>
	<div style="padding:30px; text-align:center;">
		<?= FHtml::settingCompanyPowerby() ?>. All rights reserved.
	</div>
</div>
<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 09/08/2017
 * Time: 15:41 CH
 */

$baseUrl = \common\components\FHtml::currentFrontendBaseUrl();
$baseUrl .= "/assets/";
/**
 * @var string $baseUrl
 * @var \yii\web\View $this
 */
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
