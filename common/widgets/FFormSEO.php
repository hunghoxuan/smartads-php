<?php
/**
 * @package   yii2-builder
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @version   1.6.0
 */

namespace common\widgets;

use common\components\FContent;
use common\components\FHtml;
use common\widgets\formfield\FormSEO;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Inflector;
use yii\widgets\ActiveField;


class FFormSEO extends FormSEO
{
    public function run()
    {
        if (empty($this->view_path))
            $this->view_path = '../formfield/views/_form_seo';

        return parent::run();
    }
}