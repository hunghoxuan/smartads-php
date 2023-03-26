<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use backend\models\SettingsSchema;
use common\components\FFile;
use common\components\FHtml;
use common\widgets\BaseWidget;
use common\widgets\formfield\FieldEdit;
use iutbay\yii2kcfinder\KCFinderAsset;
use kartik\form\ActiveForm;
use kartik\form\ActiveFormAsset;
use yii\base\InvalidCallException;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;

class FActiveFormModal extends FActiveForm
{
    public $is_modal = true;

}