<?php
/**



 * This is the customized model class for table "Book".
 */
use common\components\FHtml;
use common\widgets\FActiveForm;
use common\widgets\FFormTable;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormObjectFile;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

/* @var $this yii\web\View */
/* @var $model backend\modules\book\models\Book */
/* @var $form yii\widgets\ActiveForm */
