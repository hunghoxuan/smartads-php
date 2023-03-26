<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use common\components\FConstant;
use common\config\FSettings;
use yii\base\Exception;
use Yii;
use yii\base\Theme;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\helpers\Html;


class FView extends View
{
    const POS_AJAX_COMPLETE = 6;

    public function render($view, $params = [], $context = null)
    {
        $viewFile = $this->findViewFile($view, $context);
        return $this->renderFile($viewFile, $params, $context);
    }

    public function renderFile($viewFile, $params = [], $context = null)
    {
        $content = parent::renderFile($viewFile, $params, $context);
        foreach ($params as $key => $value) {
            if (is_array($value) || is_object($value))
                unset($params[$key]);
        }
        $content = FHtml::strReplaceTokens($content, $params);
        return $content;
    }

    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];

        if (!empty($this->jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_READY])) {
                $js = "jQuery(document).ready(function () {\n" . implode("\n", $this->js[self::POS_READY]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }

            // Hung: to re-register JS files after load Ajax
            if (!empty($this->js[self::POS_AJAX_COMPLETE])) {
                $js = "jQuery(document).ajaxComplete(function () {\n" . implode("\n", $this->js[self::POS_AJAX_COMPLETE]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }

            if (!empty($this->js[self::POS_LOAD])) {
                $js = "jQuery(window).load(function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    public function registerJs($js, $position = self::POS_READY, $key = null)
    {
        $key = $key ?: md5($js);
        $this->js[$position][$key] = $js;
        if ($position === self::POS_READY || $position === self::POS_LOAD) {
            JqueryAsset::register($this);
        }
    }

    public function registerJsFile($url, $options = [], $key = null)
    {
        $position1 = self::POS_END;
        if (!is_array($options)) {
            $position1 = $options;
            $options = [];
        }

        $url = Yii::getAlias($url);
        $key = $key ?: $url;
        $depends = ArrayHelper::remove($options, 'depends', []);

        if (empty($depends)) {
            $position = ArrayHelper::remove($options, 'position', $position1);
            $this->jsFiles[$position][$key] = Html::jsFile($url, $options);
        } else {
            $this->getAssetManager()->bundles[$key] = new AssetBundle([
                'baseUrl' => '',
                'js' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'jsOptions' => $options,
                'depends' => (array) $depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }
}