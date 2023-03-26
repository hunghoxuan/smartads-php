<?php

use pendalf89\filemanager\models\Tag;
use pendalf89\filemanager\Module;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<?php $form = ActiveForm::begin(['action' => '?', 'method' => 'get']) ?>
	<?= $form->field($model, 'tagIds')->widget(\kartik\select2\Select2::className(), [
		'maintainOrder' => true,
		'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
		'options' => ['multiple' => true],
		'addon' => [
			'append' => [
				'content' => Html::submitButton(Module::t('main', 'Search'), ['class' => 'btn btn-primary']),
				'asButton' => true
			]
		]
	])->label(false) ?>
<?php ActiveForm::end() ?>
