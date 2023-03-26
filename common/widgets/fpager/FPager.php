<?php
namespace common\widgets\fpager;

use common\components\FHtml;
use common\widgets\FLinkPager;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

class FPager extends FLinkPager
{
    const PAGEVIEW_DEFAULT = '';
    public $pageSizeList = [5, 10, 20, 30];
    public $total_this_page;
    public $keyword;
    public $total;

    public $pager_layout = '<div class="row"><div class="col-md-12">{pageButtons} <div class="pull-right col-md-2 pagination">{pageSizeList} </div><div class="pull-right col-md-2 pagination">{goToPage} </div></div></div>';
    public $sizeListHtmlOptions = [];
    public $goToPageHtmlOptions = ['placeholder' => 'Go to page', 'style' => 'width:50px'];
    public $pager_view = '';

    // e.g. &page=1&per-page=5
    // Pagination query string params name
    // I'd like to add underscore to vars' name to avoid any overriden
    protected $_page_param = 'page';
    protected $_page_size_param = 'per-page';


    public function init()
    {
        parent::init();

        $this->_page_param = $this->pagination->pageParam;
        $this->_page_size_param = $this->pagination->pageSizeParam;

        $currentPageSize = $this->pagination->getPageSize();

        // Push current pageSize to $this->pageSizeList,
        // unique to avoid duplicating
        if (!in_array($currentPageSize, $this->pageSizeList)) {
            array_unshift($this->pageSizeList, $currentPageSize);
            $this->pageSizeList = array_unique($this->pageSizeList);

            // Sort
            sort($this->pageSizeList, SORT_NUMERIC);
        }
    }

    public function run()
    {
        //2017/3/24
        if ($this->total_this_page == 0) {
            echo FHtml::showEmptyMessage();
            return;
        }

        if (!empty($this->pager_view)) {
            return $this->render($this->pager_view, [
                'total_this_page' => $this->total_this_page,
                'total' => $this->pagination->totalCount,
                'keyword' => $this->keyword
            ]);
        }

        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        // Register our widget assets
        FPagerAsset::register($this->getView());

        // Params will be passed to javascript
        $jsOptions = [
            'pageParam' => $this->_page_param,
            'pageSizeParam' => $this->_page_size_param,

            // Current url
            'url' => $this->pagination->createUrl($this->pagination->getPage())
        ];

        // Register inline javascript codes
        // call init method, pass params
        $this->getView()->registerJs("demoPagerWidget.init(" . Json::encode($jsOptions) . ");");

        return preg_replace_callback("/{(\\w+)}/", function ($matches) {
            $sub_section_name = $matches[1];
            $sub_section_content = $this->renderSection($sub_section_name);

            return $sub_section_content === false ? $matches[1] : $sub_section_content;
        }, $this->pager_layout);
    }

    protected function renderSection($name)
    {
        switch ($name) {
            case 'pageButtons':
                // Call inherited renderPageButtons() method
                return $this->renderPageButtons();
            case 'pageSizeList':
                // Render sub section, page size dropDownList
                return $this->renderPageSizeList();
            case 'goToPage':
                // Render sub section, go to page textInput
                return $this->renderGoToPage();
            default:
                return false;
        }
    }

    protected function renderPageButtons()
    {
        if ($this->isOnePage())
            return '<div class="text-center">' . FHtml::t('common', 'Total <b>{total}</b> items', ['total' => $this->total]) . '</div>';

        if ($this->pagination->totalCount == 0) {
            return '<div class="text-center">' . FHtml::t('common', 'No Items') . '</div>';
        } else {
            return parent::renderPageButtons();
        }
    }

    protected function isOnePage()
    {
        if ($this->pagination->getPageCount() < 2 && $this->hideOnSinglePage) {
            return true;
        }
        return false;
    }

    private function renderPageSizeList()
    {
        return '';
        if ($this->isOnePage())
            return "";

        return '<table no-bordered><tr><td class="small">' . FHtml::t('common', 'Display') . ': &nbsp;</td><td>' . Html::dropDownList($this->_page_size_param,
            $this->pagination->getPageSize(),
            array_combine($this->pageSizeList, $this->pageSizeList),
            $this->sizeListHtmlOptions) . '</td></tr></table>';
    }

    private function renderGoToPage()
    {
        return '';
        if ($this->isOnePage())
            return "";

        $current_page = 1;
        $params = Yii::$app->getRequest()->queryParams;
        if (isset($params[$this->_page_param])) {
            $current_page = intval($params[$this->_page_param]);
            if ($current_page < 1) {
                $current_page = 1;
            } elseif ($current_page > $this->pagination->getPageCount()) {
                $current_page = $this->pagination->getPageCount();
            }
        }
        $pages = [];
        for ($i = 0; $i < $this->pagination->getPageCount(); $i = $i + 1)
            $pages[] = $i;

        return '<table no-bordered><tr><td class="small">Jump: &nbsp;</td><td>' . Html::textInput($this->_page_param,
            $current_page,
            $this->goToPageHtmlOptions) . '</td></tr></table>';
    }
}
