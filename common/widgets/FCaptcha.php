<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use common\components\FHtml;
use kartik\form\ActiveField;
use kartik\widgets\FileInput;
use maksyutin\duallistbox\Widget;
use yii\base\UnknownPropertyException;
use yii\captcha\Captcha;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\JsExpression;

class FCaptcha extends Captcha
{
    public $captchaAction = '/captcha';
    public $template = "<div class='row'><div class='col-md-2'>{image}</div><div class='col-md-10'>{input}</div></div> ";
}