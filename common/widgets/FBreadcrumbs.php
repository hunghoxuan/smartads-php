<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-28
 * Time: 10:06
 */
namespace common\widgets;

use common\components\FHtml;
use frontend\components\Helper;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;

class FBreadcrumbs extends Breadcrumbs
{
    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => FHtml::t('common', 'Home'),
                'url' => '',
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        echo Html::tag($this->tag, implode("<small style='color: grey'>&nbsp;/&nbsp;</small>", $links), $this->options);
    }

    public function renderItem($link, $template)
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);
            $link = Html::a($label, FHtml::createUrl($link['url']), $options);
        } else {
            $link = $label;
        }
        return strtr($template, ['{link}' => $link]);
    }
}