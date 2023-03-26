<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-31
 * Time: 09:42
 */
namespace common\widgets;

use common\components\FPagination;
use yii\widgets\LinkPager;
use yii\helpers\Html;

class FLinkPager extends LinkPager
{
    public function init() {
        if (!isset($this->pagination))
            $this->pagination = new FPagination();

        return parent::init();
    }
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => empty($class) ? $this->pageCssClass : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        $url = new FPagination();
        return Html::tag('li', Html::a($label, $url->createUrl($page), $linkOptions), $options);
    }

    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();
        $currentPage = $currentPage - 1; //HUNG: OK

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }
}