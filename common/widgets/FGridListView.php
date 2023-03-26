<?php

/**
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @version   3.1.2
 */

namespace common\widgets;

use common\components\FConfig;
use common\components\FHtml;
use dosamigos\ckeditor\CKEditorWidgetAsset;
use kartik\base\Config;
use kartik\grid\GridView;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\jui\JuiAsset;
use yii\web\JsExpression;
use Closure;
use yii\widgets\LinkPager;

/**
 * Enhances the Yii GridView widget with various options to include Bootstrap specific styling enhancements. Also
 * allows to simply disable Bootstrap styling by setting `bootstrap` to false. Includes an extended data column for
 * column specific enhancements.
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class FGridListView extends FGridView
{
    public function init()
    {
        $this->view = FHtml::getRequestParam('view');
        if (empty($this->view)) {
            $this->view = FListView::VIEW_LIST;
        }

        return parent::init();
    }

}
