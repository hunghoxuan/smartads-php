<?php

/**
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @version   3.1.2
 */

namespace common\widgets;

use common\components\FConfig;
use common\components\FContent;
use common\components\FExcel;
use common\components\FHtml;
use common\components\FModel;
use common\components\FView;
use dosamigos\ckeditor\CKEditorWidgetAsset;
use kartik\base\Config;
use kartik\grid\GridExportAsset;
use kartik\grid\GridFloatHeadAsset;
use kartik\grid\GridPerfectScrollbarAsset;
use kartik\grid\GridResizeColumnsAsset;
use kartik\grid\GridResizeStoreAsset;
use kartik\grid\GridView;
use kartik\grid\GridViewAsset;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
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
use yii\web\View;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/**
 * Enhances the Yii GridView widget with various options to include Bootstrap specific styling enhancements. Also
 * allows to simply disable Bootstrap styling by setting `bootstrap` to false. Includes an extended data column for
 * column specific enhancements.
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class FGridView extends GridView
{
    const FORM_QUICK_ADD = '_form_add';

    public $orderUrl;
    public $sort_enabled = true;
    public $object_type;
    public $sort_field;

    public $filterEnabled = true;
    public $default_fields = [];
    public $filter_fields = [];

    //public $views = ['list', 'image', 'chart' => '<i class="fa fa-pie-chart"></i>', 'calendar' => '<span class="glyphicon glyphicon-calendar"></span>', 'report' => '<i class="fa fa-line-chart" aria-hidden="true"></i>'];
    //public $views = ['list', 'image', 'chart' => '<i class="fa fa-pie-chart"></i>'];
    public $views = [];

    public $view = '';

    public $form_enabled = false;
    public $form_view = '_form_add';
    public $form_field_template = '{object}[{column}]';
    public $form_fields = [];

    public $search_enabled = false;
    public $search_view = '_form_search';
    public $search_field_template = '{object}Search[{column}]';
    public $search_fields = [];

    public $display_type;
    public $render_type = FConfig::RENDER_TYPE_AUTO;
    public $edit_type;
    public $edit_type_default = FHtml::EDIT_TYPE_INPUT;

    public $readonly = null;
    public $canEdit = null;
    public $detail_view = 'update'; //view file to display Detail model

    public $sortOptions = [];
    public $sortAxis = 'y';

    public $field_id = ['id', 'product_id'];
    public $field_name = ['name', 'title', 'username'];
    public $field_image = ['image', 'avatar'];
    public $field_description = ['description', 'overview'];
    public $field_business = [];
    public $field_count = ['count_views', 'count_likes', 'count_purchase'];
    public $field_group = ['category_id', 'type', 'status', 'is_active', 'is_hot', 'is_top', 'is_promotion'];
    public $field_user = ['created_date', 'created_user', 'created_at', 'created_by'];
    public $itemView;
    public $layout = "{toolbar}\n{items}\n{summary}\n{pager}";

    public $actionLayout = "{delete}";
    public $emptyMessage = null;
    public $title = '';
    public $columns_auto_arrange = false;
    public $isShowAll = false;
    public $pager = [];
    public $filter = ['category_id', 'status', 'type', 'is_active', 'is_hot', 'is_top', 'is_promotion'];
    public $attributes;
    public $actionColumn;

    public $registerJS = true;
    public $dataColumnClass = 'common\widgets\FDataColumn';
    public $export_type;
    public $is_print;
    public $showToolbarBeforePjax = false;
    public $showCreateButton = false;
    public $showSearchButton = false;

    public function getPjaxContainer() {
        return FHtml::getPjaxContainerId($this->id);
    }

    protected function isBasicRenderType() {
        return false;
    }

    protected function beginPjax()
    {
        if ($this->showToolbarBeforePjax) {
            echo $this->showToolbar();
        }

        if (!$this->pjax) {
            return;
        }
        $view = $this->getView();
        if (empty($this->pjaxSettings['options']['id'])) {
            $this->pjaxSettings['options']['id'] = $this->options['id'] . '-pjax';
        }
        $container = 'jQuery("#' . $this->pjaxSettings['options']['id'] . '")';
        $js = $container;
        if (ArrayHelper::getValue($this->pjaxSettings, 'neverTimeout', true)) {
            $js .= ".on('pjax:timeout', function(e){e.preventDefault()})";
        }
        $loadingCss = ArrayHelper::getValue($this->pjaxSettings, 'loadingCssClass', 'kv-grid-loading');
        $postPjaxJs = "setTimeout({$this->_gridClientFunc}, 2500);";
        $pjaxCont = '$("#' . $this->pjaxSettings['options']['id'] . '")';
        if ($loadingCss !== false) {
            if ($loadingCss === true) {
                $loadingCss = 'kv-grid-loading';
            }
            $js .= ".on('pjax:send', function(){{$pjaxCont}.addClass('{$loadingCss}')})";
            $postPjaxJs .= "{$pjaxCont}.removeClass('{$loadingCss}');";
        }
        $postPjaxJs .= "\n" . $this->_toggleScript;
        if (!empty($postPjaxJs)) {
            $event = 'pjax:complete.' . hash('crc32', $postPjaxJs);
            $js .= ".off('{$event}').on('{$event}', function(){{$postPjaxJs}})";
        }
        if ($js != $container) {
            $view->registerJs("{$js};");
        }

        Pjax::begin($this->pjaxSettings['options']);
        echo '<div class="kv-loader-overlay"><div class="kv-loader"></div></div>';
        echo ArrayHelper::getValue($this->pjaxSettings, 'beforeGrid', '');
    }

    protected function renderPjax() {

    }

    public function getFormView() {
        if (!empty($this->form_view))
            return $this->form_view;

        if (!empty($this->form_fields))
            return 'dynamic';

        return '';
    }

    protected function isPrint() {
        return !empty($this->is_print);
    }

    protected function isExport() {
        return !empty($this->export_type);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->isBasicRenderType()) {
            parent::init();
            return;
        }

        FHtml::showCurrentMessages();

        if ($this->emptyMessage == 'default' || $this->emptyMessage == true)
            $this->emptyMessage = FHtml::showEmptyMessage();

        if (!is_array($this->field_name))
            $this->field_name = [$this->field_name];
        if (!is_array($this->field_description))
            $this->field_description = [$this->field_description];
        if (!is_array($this->field_business))
            $this->field_business = [$this->field_business];
        if (!is_array($this->field_group))
            $this->field_group = [$this->field_group];

        $this->striped = isset($this->striped) ? $this->striped : FHtml::config('FGridView::striped', false, [], 'Theme', FHtml::EDITOR_BOOLEAN);
        $this->condensed = isset($this->condensed) ? $this->condensed : FHtml::config('FGridView::condensed', false, [], 'Theme', FHtml::EDITOR_BOOLEAN);
        $this->bordered = isset($this->bordered) ? $this->bordered : FHtml::config('FGridView::bordered', false, [], 'Theme', FHtml::EDITOR_BOOLEAN);
        $this->bordered = true;
        $this->display_type = empty($this->display_type) ? FHtml::getRequestParam(['view'], FHtml::settingApplication('Grid Display Type', '')) : $this->display_type;
        $this->is_print = empty($this->is_print) ? FHtml::getRequestParam(['print']) : '';
        $this->export_type = empty($this->export_type) ? FHtml::getRequestParam(['export']) : '';

        if (empty($this->display_type))
            $this->display_type = $this->view;

        if ($this->display_type == FHtml::DISPLAY_TYPE_PRINT)
            $this->readonly = true;

        if ($this->isExport()) {
            $this->export();
            exit();
        }

        //Init Filters, remove excluded filters defined in params.php
        $excluded_filters = array_merge(['lang'], FConfig::setting('admin_grid_excluded_filters', ['is_active', 'is_top']));
        $this->filter = FContent::arrayRemove($this->filter, $excluded_filters);

        // Init Views, remove excluded views defined in params.php
        $excluded_views = array_merge([], FConfig::setting('admin_grid_excluded_views', []));
        $show_views = FConfig::setting('admin_grid_show_views');
        if (!$show_views) {
            $this->views = [];
        }
        else if (!is_array($excluded_views) && !is_string($excluded_views) && $excluded_views) {
            $this->views = [];
        } else if (is_string($excluded_views)) {
            $excluded_views = FHtml::decode($excluded_views);
            $this->views = FContent::arrayRemove($this->views, $excluded_views);
        } else if (is_array($excluded_views)) {
            $this->views = FContent::arrayRemove($this->views, $excluded_views);
        }

        foreach ($this->views as $i => $view) {
            if ($view == $this->view) // default
            {
                $this->views[$i] = 'grid';
            }
        }

        if (FHtml::currentDevice()->isMobile()) {
            $this->display_type = FListView::VIEW_GRID_BIG;
        }

        $this->pjax = isset($this->pjax) ? true : false;

        $this->form_enabled = $this->form_enabled ? $this->form_enabled : FHtml::getRequestParam('form_enabled', false);
        $this->search_enabled = $this->search_enabled ? $this->search_enabled : FHtml::getRequestParam('search_enabled', false);


        //2017/3/5
        // get Edit_Type
        if (!isset($this->canEdit))
            $this->canEdit = FHtml::isAuthorized('update', $this->object_type);

        if (!isset($this->readonly))
            $this->readonly = !$this->canEdit;

        if ($this->readonly)
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;

        $this->edit_type = FHtml::getRequestParam('edit_type', $this->edit_type);

        if (!$this->canEdit) {
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;
        }

        if (empty($this->edit_type)) {
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;
            //$this->edit_type = FHtml::settingPageView('GRID EDIT TYPE', FHtml::getRequestParam('edit_type', FHtml::EDIT_TYPE_VIEW), [FHtml::EDIT_TYPE_VIEW, FHtml::EDIT_TYPE_INLINE, FHtml::EDIT_TYPE_INPUT], '', FHtml::EDITOR_SELECT);
        }

        //Hung: intitialize objects
        if (empty($this->object_type)) {
            $this->object_type = FHtml::getTableName($this->filterModel);
        }

        if ($this->id == 'crud-datatable')
            $this->id = $this->id . BaseInflector::camelize($this->object_type);

        if (!isset($this->dataProvider)) {
            $this->dataProvider = new ActiveDataProvider();
        }

        if (!isset($this->form_enabled))
            $this->form_enabled = FHtml::getRequestParam('form_enabled', false);

        if ($this->isShowAll || strpos( $this->layout, '{pager}') === false || !isset($this->pager) || $this->pager === false)
        {
            $this->dataProvider->pagination = false;
        }

        if ($this->isPrint()) {
            $this->layout = '{toolbar}{items}{pager}';
            $this->filterModel = null;
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;
        }

        if (isset($this->filterModel) && method_exists($this->filterModel, 'isSqlDb') && !$this->filterModel::isSqlDb()) {
            $this->filterModel = null;
        }

        parent::init();
    }

    protected function export() {
        $models  = $this->dataProvider->getModels();
        $columns = $this->columns;

        FExcel::getInstance()->setModels($models)->setColumns($columns)->prepareDataExcel()->downloadFile();
    }

    protected function initSorting()
    {
        if ($this->sort_enabled && FHtml::field_exists($this->object_type, 'sort_order')) {
            if (empty($this->orderUrl))
                $this->orderUrl = '/admin/sort-order';

            $classes = isset($this->options['class']) ? $this->options['class'] : '';
            $classes .= ' sortable';
            $this->options['class'] = trim($classes);

            $view = $this->getView();
            JuiAsset::register($view);

            $url = Url::toRoute($this->orderUrl);
            $id = $this->getId();

            $sortOpts = array_merge($this->sortOptions, [
                'helper' => new JsExpression('function(e, ui) {
                    ui.children().each(function() {
                       $(this).width($(this).width());
                    });
                    return ui;
                }'),
                'update' => new JsExpression("function(e, ui) {
                jQuery('#{$this->id}').addClass('sorting');
                result = [];
                $('#{$id} tbody').children().each(function( index, element ) { result.push($(this).attr('data-key')); });
                //console.log('{$url}?object_type={$this->object_type}&sort_orders=' + result);
                jQuery.ajax({
                    type: 'POST',
                    url: '{$url}?object_type={$this->object_type}&sort_orders=' + result,
                    data: {
                        key: ui.item.data('key'),
                        pos: ui.item.index()
                    },
                    success: function (data) {
                        //alert(data);
                        refreshPage('#{$this->id}-pjax');
                        //$.pjax.reload('#{$this->id}', {timeout : false})
                    },
                    complete: function() {
                        jQuery('#{$this->id}').removeClass('sorting');
                    }
                });
            }")
            ]);

            if ($this->sortAxis) $sortOpts['axis'] = $this->sortAxis;

            $sortJson = Json::encode($sortOpts);

            $view->registerJs("jQuery('#{$id} tbody').sortable($sortJson);", FView::POS_AJAX_COMPLETE);
            $view->registerJs("jQuery('#{$id} tbody').sortable($sortJson);");

            FHtml::registerSortOrder($this->object_type, $this->id);
        }
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function run()
    {
        if ($this->isBasicRenderType()) {
            parent::run();
            return;
        }

        $listView = self::initListView();

        self::initSorting();

        $id = FHtml::getRequestParam(['id', '_id']);
        if (!empty(FHtml::getRequestParam('id')) && !empty($this->detail_view)) {
            echo $this->renderDetailView($id);
            return;
        }

        if (isset($listView)) {

            //$this->init();
            $this->initToggleData();

            $this->initExport();

            if ($this->export !== false && isset($this->exportConfig[self::PDF])) {
                Config::checkDependency(
                    'mpdf\Pdf',
                    'yii2-mpdf',
                    "for PDF export functionality. To include PDF export, follow the install steps below. If you do not " .
                    "need PDF export functionality, do not include 'PDF' as a format in the 'export' property. You can " .
                    "otherwise set 'export' to 'false' to disable all export functionality"
                );
            }

            $this->initHeader();
            $this->initBootstrapStyle();
            $this->containerOptions['id'] = $this->options['id'] . '-container';
            Html::addCssClass($this->containerOptions, 'kv-grid-container');
            Html::addCssStyle($this->containerOptions, 'overflow-x:hidden !important');

            $this->registerAssets();

            $this->renderPanel();
            $this->initLayout();
            $this->pjaxSettings['timeout'] = 100;
            $this->beginPjax();

            $listView->actionColumn = $this->actionColumn;
            $listView->emptyMessage = $this->emptyMessage;
            $listView->layout = $this->layout;
            $listView->toolbar = $this->renderToolbar();
            $listView->object_type = $this->object_type;

            $this->beginDetailView($id);

            $listView->run();

            $this->endDetailView($id);

            $this->endPjax();
        }
        else {
            if (!empty($this->getItemsCount()) || FHtml::currentAction() !== 'index' || empty($this->emptyMessage) || !empty($_REQUEST)) {
                $this->beginDetailView($id);

                parent::run();

                $this->endDetailView($id);
            }
            else {
                echo $this->emptyMessage;
            }
        }
    }

    protected function beginDetailView($id) {
        if (!empty($id) && !empty($this->detail_view)) {
            echo "<div class='col-md-4'>";
        }
    }

    protected function renderDetailView($id) {
        if (empty($this->detail_view))
            return;
        return FHtml::render($this->detail_view, ['' => '', 'model' => $this->findOne($id)]);
    }

    protected function endDetailView($id) {
        if (!empty($id) && !empty($this->detail_view)) {
            $detail = $this->renderDetailView($id);
            echo "</div><div class='col-md-8'>$detail</div>";
        }
    }

    public function findOne($id) {
        return FHtml::findOne($this->object_type, $id);
    }

    public function getItemsCount() {
        return static::getItemsTotalCount();
    }

    private $itemsCount = null;
    public function getItemsTotalCount() {
        if (isset($this->itemsCount))
            return $this->itemsCount;

        if (is_array($this->dataProvider))
            $this->itemsCount = count($this->dataProvider);
        else
            $this->itemsCount = isset($this->dataProvider) ? $this->dataProvider->getCount() : 0;
        return $this->itemsCount;
    }

    public function setDataProvider($value) {
        $this->itemsCount = null;
        $this->dataProvider = $value;
    }

    public function getModelsCount() {
        return $this->getItemsCount();
    }

    protected function initListView() {
        $listtype = str_replace('_', '', $this->display_type);
        $listView = null;
        if ($listtype == FListView::VIEW_CHART) {
            $listView = new FGridViewChart([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'columns' => !empty($this->filter) ? $this->filter : $this->field_group,
                'toolbar' => self::renderToolbar(),
                'object_type' => $this->object_type,
                'itemSize' => 3
            ]);
            return $listView;
        }
        elseif ($listtype == FListView::VIEW_CALENDAR) {
            $listView = new FGridViewCalendar([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'columns' => !empty($this->filter) ? $this->filter : $this->field_group,
                'toolbar' => self::renderToolbar(),
                'object_type' => $this->object_type,
                'itemSize' => 3
            ]);
            return $listView;
        }
        elseif ($listtype == FListView::VIEW_REPORT) {
            $listView = new FGridViewReport([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'columns' => !empty($this->filter) ? $this->filter : $this->field_group,
                'toolbar' => self::renderToolbar(),
                'object_type' => $this->object_type,
                'itemSize' => 3
            ]);
            return $listView;
        }
        elseif ($listtype == FListView::VIEW_GRID_BIG) {
            $listView = new FListView([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'display_type' => \common\widgets\FListView::VIEW_GRID,
                'toolbar' => self::renderToolbar(),
                'itemSize' => 3
            ]);
        }
        else if ($listtype == FListView::VIEW_GRID_SMALL) {
            $listView = new FListView([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'display_type' => \common\widgets\FListView::VIEW_GRID,
                'toolbar' => self::renderToolbar(),
                'itemSize' => 2
            ]);
        }
        elseif (!empty($this->display_type) && !FHtml::isInArray($this->display_type, ['print', 'grid', FHtml::DISPLAY_TYPE_WIDGET])) {
            $listView = new FListView([
                'id' => $this->id,
                'field_image' => $this->field_image,
                'field_name' => $this->field_name,
                'dataProvider' => $this->dataProvider,
                'display_type' => $this->display_type,
                'toolbar' => self::renderToolbar(),
            ]);
        }
        if (isset($listView))
        {
            if (empty($this->field_description))
                $this->field_description = [];

            if (empty($this->field_name))
                $this->field_name = [];

            foreach ($this->columns as $column) {
                if (property_exists($column, 'attribute') && !in_array($column->attribute, $this->field_description) && !in_array($column->attribute, $this->field_name) && !in_array($column->attribute, ['id', 'image', 'banner', 'thumbnail'])) {
                    $this->field_description[] = $column->attribute;
                }
            }

            if (!empty($this->field_name))
                $listView->field_name = $this->field_name;
            if (!empty($this->field_description))
                $listView->field_description = $this->field_description;
            if (!empty($this->field_group))
                $listView->field_group = $this->field_group;
            if (!empty($this->field_user))
                $listView->field_user = $this->field_user;
            if (!empty($this->field_id))
                $listView->field_id = $this->field_id;
            if (!empty($this->field_business))
                $listView->field_business = $this->field_business;
            if (!empty($this->field_count))
                $listView->field_count = $this->field_count;
            if (!empty($this->itemView))
                $listView->itemView = $this->itemView;
        }

        return $listView;
    }

    public function getTitle() {
        if (empty($this->title))
            $this->title = FHtml::t($this->object_type, BaseInflector::camel2words($this->object_type));
        return $this->title;
    }

    protected function initExport() {
        if (is_string($this->export))
            return;

        $this->export['target'] = self::TARGET_SELF;
        parent::initExport();
        $title = self::getTitle();
        $isFa = $this->export['fontAwesome'];

        foreach ($this->exportConfig as $key => $items) {
//            if (in_array($key, [self::TEXT, self::HTML, self::PDF]))
//                unset($this->exportConfig[$key]);

            $file_name = $this->object_type . '_' . FHtml::getFriendlyFileName($title);
            $this->exportConfig[$key]['filename'] = $file_name;
            $this->exportConfig[$key]['alertMsg'] = FHtml::t('button', 'Export') . ': [' . $file_name . ']';
        }
        $this->export = array_replace_recursive(
            [
                'label' => '',
                'icon' => 'share-square-o',
                'messages' => [
                    'allowPopups' => FHtml::t(
                        'message',
                        'Disable any popup blockers in your browser to ensure proper download.'
                    ),
                    'confirmDownload' => FHtml::t('message', 'Ok to proceed'),
                    'downloadProgress' => FHtml::t('message', 'Generating the export file. Please wait...'),
                    'downloadComplete' => FHtml::t(
                        'message',
                        'Request submitted! You may safely close this dialog after saving your downloaded file.'
                    ),
                ],
                'options' => ['class' => 'btn btn-default', 'title' => FHtml::t('button', 'Export')],
                'menuOptions' => ['class' => 'dropdown-menu dropdown-menu-right '],
            ],
            $this->export
        );
    }

    protected function showToolbar() {
        $obj_name = self::getTitle();
        $result = '';
        $columns = $this->attributes;

        //register JS
        if (!isset($this->registerJS)) {
            $this->registerJS = true;
        } else if (isset($this->registerJS) && $this->registerJS instanceof Closure) {
            call_user_func($this->registerJS);
            $this->registerJS = false;
        }

        //check if not exist form_view then ignore
        if (!empty($this->form_view)) {
            $this->form_field_template = FHtml::isAjaxRequest() ?  '{object}[{column}]' : '{object}[{column}]';
            if ($this->registerJS)
                FHtml::registerPlusJS($this->object_type, !empty($this->form_fields) ? $this->form_fields : $columns, $this->getPjaxContainer(), '{object}[{column}]', $this->default_fields, 'POST');

        } else if (!empty($this->form_fields)) { //predefined form_fields
            if ($this->registerJS)
                FHtml::registerPlusJS($this->object_type, !empty($this->form_fields) ? $this->form_fields : $columns, $this->getPjaxContainer(), '{object}[{column}]', $this->default_fields, 'POST');

            $this->toolbar = FHtml::showPlusFormDynamic($this->object_type, $this->form_fields, $this->getPjaxContainer(), $this->form_field_template); // replate Toolbar
        } else { // auto form_fields
            //$this->filterModel = null; //turn off filter
            $this->form_field_template = FHtml::isAjaxRequest() ?  '{object}Search[{column}]' : '{object}Search[{column}]';

            if ($this->registerJS)
                FHtml::registerPlusJS($this->object_type, $columns, $this->getPjaxContainer(), $this->form_field_template, $this->default_fields, 'GET');
        }

        //check if not exist search_view then ignore
        if (!empty($this->search_view)) {
            if ($this->registerJS)
                FHtml::registerSearchJS($this->object_type , $columns, $this->getPjaxContainer(), '{object}Search[{column}]', [], 'POST');
        }

        //add create form
        if ($this->isFormEnabled() && !empty($this->form_view)) {
            if ($this->form_view != 'dynamic') {
                $result .= FHtml::showPlusFormView($this->object_type, [], $this->getPjaxContainer(), $this->form_view);
            } else if ($this->form_view == 'dynamic') {
                $result .= FHtml::showPlusFormDynamic($this->object_type, !empty($this->form_fields) ? $this->form_fields : $this->getCells(), $this->getPjaxContainer());
            }
        } //or add search form
        else if ($this->isSearchEnabled() && !empty($this->search_view)) {
            if ($this->search_view != 'dynamic') {
                $result .= FHtml::showPlusFormView($this->object_type . 'search' , [], $this->getPjaxContainer(), $this->search_view);
                //$result .= "<br/>" . FHtml::showSearchButtons($this->object_type, '{search}{cancel}', $this->getPjaxContainer());
            } else if ($this->search_view == 'dynamic') {
                $result .= FHtml::showPlusFormDynamic($this->object_type . 'search', !empty($this->search_fields) ? $this->search_fields : $this->getCells(), $this->getPjaxContainer());
            }
        } else if (!$this->isPrint()) {
            $result = parent::renderToolbar();
        }

        $content = FHtml::showPrintHeader($obj_name);;
        if ($this->isPrint()) {
            if (empty($result))
                $content .= "<div class='hidden-print row'><div class='col-md-12'><div class='pull-right'>" .  self::renderToggleData() . self::renderExport() . '</div></div></div> ';
        }

        $content = $content . (!empty($result) ? "<div class='hidden-print row'><div class='col-md-12'>" . $result . '</div></div>' : '');

        $filter = $this->renderFilter();
        $content = $content . $filter;

        //auto refresh filters dropdown list
        $this->registerJS();

        $content = FHtml::strReplace($content, ['{export}' => $this->renderExport(), '{toggleData}' => $this->renderToggleData()]);

        return $content;
    }

    protected function registerJS() {
        FHtml::registerAutoRefreshJS($this->getPjaxContainer());
    }

    protected function renderToolbar($populateAll = false)
    {
        //if already showed before Pjax, return ''
        if ($this->showToolbarBeforePjax)
            return '';

        $content = $this->showToolbar();

        return $content;
    }

    protected function renderFilter() {

        $result = '';

        if (is_array($this->filter)) {
            $result = FHtml::buildGridToolbar($this->object_type, $this->filter, $this->views, !$this->readonly, $this->getItemsTotalCount());
        } else if (!empty($this->filter)) {
            $result = FHtml::renderView($this->filter);
        }

        if (!empty($result))
            $result =  "<div class='row' style='text-transform: uppercase; margin-top:10px'><div class='col-md-12'> $result </div></div>";

        return $result;
    }

    public function renderTableHeader()
    {

        if ($this->isPrint()) {
            $cells = [];

            foreach ($this->columns as $index => $column) {
                if (FHtml::field_exists($column, 'attribute')) {
                    $label = FHtml::getFieldLabel($this->object_type, $column->attribute);
                    $alignment = !FHtml::isInArray($column->attribute, FHtml::FIELDS_STATUS) ? 'left' : 'center';
                    $size = key_exists('class', $column->contentOptions) ? $column->contentOptions['class'] : 'col-xs-2 col-md-2';

                } else {
                    $label = '';
                    $alignment = 'left';
                    $size = '';
                }
                $a = "<td class='$size text-$alignment'>$label</td>";
                $a = self::renderCell($a, true);
                $cells[] = $a;
            }

            $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);

            return "<thead>\n" .
                $content . "\n" .
                "</thead>";
        } else {
            $this->pjaxSettings['options'] = ['id' => $this->getPjaxContainer()];
            return self::renderTableHeader1();
        }
    }

    protected function isFormEnabled()
    {
        return $this->form_enabled;
    }

    protected function isSearchEnabled()
    {
        return $this->search_enabled;
    }

    protected function initLayout()
    {
        parent::initLayout();
    }

    //2017/3/5
    public function renderExport()
    {
        if (FHtml::currentDevice()->isMobile() || empty($this->getItemsCount()))
            return '';

        if (is_string($this->export))
            return $this->export;

        $result = '';
        if ($this->isPrint()) {
           $result .= FHtml::buttonPrint();
        } else {
            $result .= $this->renderCreate();
            $result .= $this->renderSearch();
        }

        $label = '<i class="fa fa-file-excel-o"></i>';
        $options['title'] = 'Excel';
        $options['class'] = 'btn btn-default';
        $options['data-pjax'] = '0';

        $url = Url::current(['export' => 'excel']);
        static::initCss($this->toggleDataContainer, 'btn-group');
        $result .= Html::tag('div', Html::a($label, $url, $options), $this->toggleDataContainer);

        if (!$this->isPrint()) {
            $label = '<i class="fa fa-print"></i>';
            $options['title'] = 'Print';
            $options['class'] = 'btn btn-default';
            $options['data-pjax'] = '0';
            $options['target'] = '_blank';

            $url = Url::current(['print' => 'true']);
            static::initCss($this->toggleDataContainer, 'btn-group');
            $result .= Html::tag('div', Html::a($label, $url, $options), $this->toggleDataContainer);
        }

        $this->export = $result;
        return $result;
    }

    private $_renderCreate = null;

    public function renderCreate() {
        if ($this->readonly || !$this->canEdit || empty($this->form_view) || !$this->showCreateButton)
            return '';

        if (isset($this->_renderCreate))
            return $this->_renderCreate;

        if (!empty($this->form_view)) {
            $file = FHtml::findView($this->form_view, true, true);
            if (empty($file))
                $this->form_view = '';
        }

        if (empty($this->form_view))
            return '';

        $object_type = $this->object_type;
        $result = '';

        $options = key_exists('all', $this->toggleDataOptions) ? $this->toggleDataOptions['all'] : [];
        //$options['data-pjax'] = 1;

        $form_enabled = $this->isFormEnabled();
        $label = $form_enabled ? '<span class="glyphicon glyphicon-chevron-up"></span>' : '<span class="glyphicon glyphicon-plus"></span>';
        $options['title'] = $form_enabled ? 'Close Quick Add form' : 'Open Quick Add form';
        $options['class'] = $form_enabled ? 'btn btn-default' : 'btn btn-success';

        $url = Url::current(['form_enabled' => !$form_enabled]);
        static::initCss($this->toggleDataContainer, 'btn-group');
        $result .= Html::tag('div', Html::a($label, $url, $options), $this->toggleDataContainer);

        $this->_renderCreate = $result;
        return $result;
    }

    public function renderSearch() {
        if (!$this->showSearchButton)
            return '';

        if (!empty($this->search_view)) {
            $file = FHtml::findView($this->search_view, true, true);

            if (empty($file))
                $this->search_view = '';
        }

        if (empty($this->search_view))
            return '';

        $object_type = $this->object_type;
        $result = '';

        $options = key_exists('all', $this->toggleDataOptions) ? $this->toggleDataOptions['all'] : [];

        $search_enabled = $this->isSearchEnabled();
        $label = $search_enabled ? '<span class="glyphicon glyphicon-chevron-up"></span>' : '<span class="glyphicon glyphicon-search"></span>';
        $options['title'] = $search_enabled ? 'Close Search form' : 'Open Search form';
        $options['class'] = $search_enabled ? 'btn btn-default' : 'btn btn-default';

        $url = Url::current(['search_enabled' => !$search_enabled]);
        static::initCss($this->toggleDataContainer, 'btn-group');
        $result .= Html::tag('div', Html::a($label, $url, $options), $this->toggleDataContainer);

        return $result;
    }

    //2017/3/5
    public function renderFilters()
    {
        if ($this->isBasicRenderType()) {
            parent::renderFilters();
            return;
        }

        if ($this->isPrint())
            return '';

        if (FHtml::currentDevice()->isMobile())
            return '';

        $tr_plus = '';

        if (($this->isFormEnabled() && empty($this->form_fields)) && $this->filterEnabled === true && !isset($this->filterModel)) {

            $cells = $this->getCells();
            if (empty($this->form_view) || $this->form_view == 'grid') {
                $tr_plus .= FHtml::showPlusTableRow($this->object_type, $cells, $this->getPjaxContainer(), $this->form_field_template, $this->display_type !== FHtml::DISPLAY_TYPE_WIDGET);
            }
        }

        return empty($tr_plus) ? parent::renderFilters() : $tr_plus;
    }

    public function getCells() {
        $cells = [];
        foreach ($this->columns as $column) {
            $class = get_class($column);
            /* @var $column Column */
            if (StringHelper::endsWith(get_class($column), 'ActionColumn')) {
                $cells[] = 'action';
            } else if (FHtml::field_exists($column, 'attribute')) {
                $cells[] = $column->attribute;
            } else if (FHtml::field_exists($column, 'attribute') && $column->group == true && $column->groupedRow == true) {
                $cells[] = 'group';
            } else {
                $cells[] = 'null';
            }
        }

        return $cells;
    }

    public function renderToggleData()
    {
        if (FHtml::currentDevice()->isMobile())
            return '';

        $result = '';
        if (!$this->toggleData) {
            return '';
        }

        $pagination = isset($this->dataProvider) ? $this->dataProvider->getPagination() : null;

        if (!isset($pagination) || $this->getItemsTotalCount() <= 0) {
            return '';
        }

        $maxCount = ArrayHelper::getValue($this->toggleDataOptions, 'maxCount', false);
        if ($maxCount !== true && (!$maxCount || (int)$maxCount <= $this->getItemsTotalCount())) {
            $result = '';
        }
        $result .= parent::renderToggleData();

        return $result;
    }

    protected function initColumnsParent() {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        foreach ($this->columns as $i => $column) {
            if (!isset($column)) {
                unset($this->columns[$i]);
                continue;
            }

            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                //remove original DataColumn class, replace by FDataColumn Class
                $class = isset($column['class']) ? $column['class'] : '';
                if (!empty($class) && StringHelper::endsWith($class, 'DataColumn'))
                    unset($column['class']);

                $column = \Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ? : FDataColumn::className(),
                    'grid' => $this,
                ], $column));
            }

            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            $this->columns[$i] = $column;
        }
    }

    protected function initColumns()
    {
        if (empty($this->edit_type) || $this->display_type == FHtml::TYPE_DEFAULT || $this->isBasicRenderType()) {
            foreach ($this->columns as $i => $column) {
                unset($column['edit_type']);
                unset($column['db_type']);
                unset($column['show_type']);
                unset($column['editor']);
                $this->columns[$i] = $column;
            }
            self::initColumnsParent(); // TODO: Change the autogenerated stub

            return;
        }

        return self::prepareColumns();
    }

    protected function getEditType() {
        return self::isEditable() ? $this->edit_type : $this->edit_type_default;
    }

    protected function getColumnClass() {
        return self::isEditable() ? FHtml::COLUMN_EDIT : FHtml::COLUMN_VIEW;
    }

    protected function isEditable($class = '') {
        if (empty($class))
            $class = $this->edit_type;

        return !empty($class) && $class != FHtml::EDIT_TYPE_VIEW;
    }

    protected function prepareColumns()
    {
        $is_existedAction = false;
        $is_existedSerial = false;

        $columns = [];

        if ((FHtml::settingDynamicGrid() || $this->render_type == FHtml::RENDER_TYPE_DB_SETTING)) {
            $this->columns = FHtml::buildGridColumns($this->object_type, $this->columns); // load columns from db
        }

        foreach ($this->columns as $column => $column_options) {

            if (is_string($column) && ($column_options instanceof Closure || is_string($column_options))) {
                $arr = [];
                $arr['format'] = 'raw';
                $arr['class'] = $this->getColumnClass();
                $arr['attribute'] = $column;
                $arr['edit_type'] = $this->edit_type;
                $arr['value'] = $column_options;
                $columns[] = $arr;
            } else if (is_string($column) && is_array($column_options)) {
                $arr = $column_options;

                if (!key_exists('format', $arr))
                    $arr['format'] = 'raw';

                if (!key_exists('class', $arr)) {

                    $arr['class'] = $this->getColumnClass();
                }

                if (!key_exists('attribute', $arr))
                    $arr['attribute'] = $column;

                if (!key_exists('edit_type', $arr) && $arr['class'] == FHtml::COLUMN_EDIT)
                    $arr['edit_type'] = $this->getEditType();

                $columns[] = $arr;
            } else if (is_string($column_options) && is_numeric($column)) {
                $column = $column_options;
                $arr = FHtml::parseAttribute($column);

                $format = $arr['format'];
                if (in_array($format, [FHtml::EDIT_TYPE_INLINE, FHtml::EDIT_TYPE_INPUT, FHtml::EDIT_TYPE_POPUP, FHtml::EDIT_TYPE_DEFAULT])) {
                    $edit_type = $format;
                    $class = !empty($class) ? $class : FHtml::COLUMN_EDIT;
                    $format = 'raw';
                } else {
                    $class = !empty($class) ? $class : FHtml::COLUMN_EDIT;
                    $edit_type = $this->getEditType();
                }

                if ($this->readonly == true) {
                    $class = FHtml::COLUMN_VIEW;
                    $edit_type = FHtml::EDIT_TYPE_VIEW;
                    $format = 'html';
                    $this->form_enabled = false;
                }

                $columns[] = [
                    'class' => $class,
                    'attribute' => $arr['attribute'],
                    'edit_type' => $edit_type,
                    'format' => $format,
                    'label' => $arr['attribute']
                ];
            } else if (is_array($column_options)) {
                $column = $column_options;
                if (FHtml::field_exists($column, 'class') && StringHelper::endsWith($column['class'], 'ActionColumn')) {
                    $is_existedAction = true;
                    $column_options['class'] = 'common\widgets\FActionColumn';
                    $column_options['actionLayout'] = FHtml::getFieldValue($column, 'actionLayout', $this->actionLayout);
                } else if (FHtml::field_exists($column, 'class') && StringHelper::endsWith($column['class'], 'SerialColumn')) {
                    $is_existedSerial = true;
                } else if (FHtml::field_exists($column, 'class') && StringHelper::endsWith($column['class'], 'ExpandRowColumn')) {
                    if (!FHtml::settingShowPreviewColumn() && FHtml::getFieldValue($column, 'visible', false) !== true)
                        continue;
                }

                $arr = $column_options;

                if (!key_exists('class', $arr)) {
                    $arr['class'] = $this->getColumnClass();
                    //echo $arr['class'];
                }

                if ($arr['class'] == FHtml::COLUMN_EDIT) {
                    if (!key_exists('edit_type', $arr))
                        $arr['edit_type'] = $this->getEditType();
                    //echo $arr['edit_type'];
                } else if ($arr['class'] == FHtml::COLUMN_VIEW) {
                    if (!key_exists('edit_type', $arr))
                        $arr['edit_type'] = FHtml::EDIT_TYPE_VIEW;
                }

                if (in_array($arr['class'], [FHtml::COLUMN_EDIT, FHtml::COLUMN_VIEW])) {
                    if (!key_exists('format', $arr))
                        $arr['format'] = 'raw';

                    if (!key_exists('attribute', $arr))
                        $arr['attribute'] = '';
                }

                if (key_exists('editableOptions', $arr))
                    unset($arr['editableOptions']);

                $columns[] = $arr;
            }
        }

        if ($this->display_type == FHtml::DISPLAY_TYPE_WIDGET) {
            if (!$is_existedAction) {
                $columns[] = [
                    'class' => 'common\widgets\FActionColumn',
                    'visible' => !$this->readonly,
                    'dropdown' => 'ajax', // Dropdown or Buttons
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '120px'
                ];
            }
            if (!$is_existedSerial) {
                $columns = array_merge([[
                    'class' => 'kartik\grid\SerialColumn',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '1%'
                ]], $columns);
            }
        }

        $this->columns = $columns;

        $image_columns = [];
        $bool_columns = [];
        $action_columns = [];
        $first_columns = [];
        $columns = [];

        foreach ($this->columns as $i => $column) {
            if (!is_array($column))
                continue;

            $format = FHtml::getFieldValue($column, 'format');
            $attribute = key_exists('attribute', $column) ? $column['attribute'] : '';

            $class =  FHtml::getFieldValue($column, 'class');
            $edit_type = FHtml::getFieldValue($column, 'edit_type');
            $db_type = FHtml::getFieldValue($column, 'db_type'); if (!empty($db_type)) unset($column['db_type']);
            $editor = FHtml::getFieldValue($column, 'editor'); if (!empty($editor)) unset($column['editor']);
            $show_type = FHtml::getFieldValue($column, 'show_type');
            if (!empty($show_type)) {
                unset($column['show_type']);
            } else {
                $show_type = FHtml::getShowType($this->object_type, $attribute);
            }

            if ($this->readonly || $this->display_type == FHtml::DISPLAY_TYPE_PRINT) {
                if ($class == FHtml::COLUMN_EDIT) {
                    $class = FHtml::COLUMN_VIEW;
                    $column['class'] = $class;
                }
            }

            if ( $class == FHtml::COLUMN_EDIT && empty($edit_type)) {
                $edit_type = $this->getEditType();
                $format = empty($format) ? $edit_type : $format;
                $column['edit_type'] = $edit_type;
            } else if ($class == FHtml::COLUMN_VIEW || isset($column['value'])) {
                $edit_type = FHtml::EDIT_TYPE_VIEW;
                $format = empty($format) ? 'raw' : $format;
                $column['edit_type'] = $edit_type;
            }

            if (in_array($format, [FHtml::EDIT_TYPE_INLINE, FHtml::EDIT_TYPE_INPUT, FHtml::EDIT_TYPE_POPUP, FHtml::EDIT_TYPE_DEFAULT]))
            {
                $column['edit_type'] = !empty($column['edit_type']) ? $column['edit_type'] : $format;
                if (!isset($column['editableOptions']) && $format == FHtml::EDIT_TYPE_DEFAULT)
                    $column['edit_type'] = $this->getEditType();

                $column['class'] = !empty($column['class']) ? $column['class'] : FHtml::COLUMN_EDIT;
                $column['format'] = 'raw';

                $edit_type = $this->getEditType();

            } else if ($format == FHtml::EDIT_TYPE_VIEW) {
                $column['edit_type'] = !empty($column['edit_type']) ? $column['edit_type'] : FHtml::EDIT_TYPE_VIEW;
                $column['class'] = !empty($column['class']) ? $column['class'] : FHtml::COLUMN_VIEW;

                $edit_type = FHtml::EDIT_TYPE_VIEW;

            } else if (empty($format)) {
                $column['edit_type'] = !empty($column['edit_type']) ? $column['edit_type'] : FHtml::EDIT_TYPE_VIEW;
                $column['class'] = !empty($column['class']) ? $column['class'] : FHtml::COLUMN_VIEW;

                $edit_type = FHtml::EDIT_TYPE_VIEW;
            } else {
                $column['edit_type'] = !empty($column['edit_type']) ? $column['edit_type'] : (isset($column['value']) ? FHtml::EDIT_TYPE_DEFAULT : $this->getEditType());
                $column['class'] = !empty($column['class']) ? $column['class'] : FHtml::COLUMN_VIEW;

                $edit_type = isset($column['value']) ? FHtml::EDIT_TYPE_VIEW : $this->getEditType();
            }

            if (key_exists('edit_type', $column)) {
                $edit_type = FHtml::getFieldValue($column, 'edit_type', $this->edit_type);
                unset($column['edit_type']); // remove key 'edit_type' because it is custom key, not allowed in DataColumn
            }

            if (key_exists('readonly', $column)) {
                unset($column['readonly']); // remove key 'edit_type' because it is custom key, not allowed in DataColumn
                $edit_type = FHtml::EDIT_TYPE_VIEW;
            }

            if (is_array($column) && !key_exists('class', $column)) {
                if (key_exists('attribute', $column))
                    $column['class'] = FHtml::getColumnClass($this->object_type, $column['attribute']);
                else
                    $column['class'] = FHtml::COLUMN_VIEW;
            }

            if (is_array($column) && !key_exists('visible', $column)) {

                if (key_exists('attribute', $column))
                    $column['visible'] = FHtml::isVisibleInGrid($this->object_type, $column['attribute']);
                else
                    $column['visible'] = true;
            }

            //2017/03/28: Get translated label
            if (!FHtml::isInArray($class, ['*SerialColumn', '*ExpandRowColumn', '*ActionColumn', '*CheckboxColumn'])) {
                $column = $this->getLabelForColumn($column);
            }

            if ($edit_type == FHtml::EDIT_TYPE_DEFAULT || $this->render_type == FHtml::RENDER_TYPE_CODE || FHtml::isInArray($column['class'], ['*DataColumn'])) {

                if (is_array($column) && !key_exists('format', $column)) {
                    $column['format'] = 'raw';
                }
            }

            if (is_array($column) && !key_exists('vAlign', $column)) {
                $column['vAlign'] = 'middle';
            }

            if (FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn', '*ActionColumn']) || (key_exists('attribute', $column) && FHtml::isInArray($column['attribute'], FHtml::getFIELDS_GROUP()))) {
                $column['hAlign'] = 'center';
            }

            if ($edit_type == FHtml::EDIT_TYPE_DEFAULT || $this->render_type == FHtml::RENDER_TYPE_CODE || FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn', '*ActionColumn'])) {
                if (key_exists('attribute', $column) && (FHtml::isInArray($column['attribute'], FHtml::FIELDS_BOOLEAN) || ($show_type == FHtml::SHOW_ACTIVE)))
                {
                    $edit_type = $this->getEditType();
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $value = FHtml::getFieldValue($model, $column1->attribute);
                        $result = empty($value) ? '' : FHtml::showActive($value, BaseInflector::camel2words($column1->attribute));
                        return $result;
                    };
                }

                $this->columns[$i] = $column;
            }

            $field = FHtml::field_exists($column, 'attribute') ? $column['attribute'] : '';

            if ($edit_type != FHtml::EDIT_TYPE_VIEW && !FHtml::isEditInGrid($this->object_type, $field)) { // check edit condition once again
                $edit_type = FHtml::EDIT_TYPE_VIEW;
            }

            if ($edit_type == FHtml::EDIT_TYPE_VIEW) {
                if (FHtml::isInArray($column['class'], ['*DataColumn','*EditableColumn', '*BooleanColumn'])) {

                    $column['class'] = FHtml::COLUMN_VIEW;
                    if (key_exists('attribute', $column) && FHtml::isInArray($column['attribute'], FHtml::FIELDS_IMAGES)) {
                        $column['hAlign'] = 'center';
                        $column['vAlign'] = 'middle';
                        if (!key_exists('value', $column)) {
                            $column['value'] = function ($model, $key, $index, $column1) {
                                $value = FHtml::getFieldValue($model, array_merge([$column1->attribute], ['image', 'thumbnail', 'banner', 'avatar']));

                                $result = FHtml::showImageThumbnail($value, FHtml::config('ADMIN_THUMBNAIL_WIDTH', '50'), FHtml::getUploadFolder(str_replace('_', '-', $this->object_type)));
                                return $result;
                            };
                        }
                    } else if (key_exists('attribute', $column) && FHtml::isInArray($column['attribute'], FHtml::FIELDS_BOOLEAN)) {
                        $column['filterType'] = GridView::FILTER_SELECT2;
                        $column['filter'] = FHtml::getComboArray('', $this->object_type, $field, true, 'id', 'name');
                        $column['filterWidgetOptions'] = [
                            'pluginOptions' => ['allowClear' => true],
                        ];
                        $column['vAlign'] = 'middle';
                        $column['hAlign'] = 'center';
                        if (!key_exists('value', $column)) {
                            $column['value'] = function ($model, $key, $index, $column1) {
//                                $value = FHtml::getFieldValue($model, $column1->attribute);
//                                $result = FHtml::showActive($value);
//                                return $result;
                                $field = $column1->attribute;
                                $result = FHtml::showModelFieldValue($model, $field, '', '', false, $this->getPjaxContainer());
                                $result = FHtml::showBooleanEditable($result, FHtml::getFieldValue($model, $field), $field, $model->id, $this->object_type, true, $this->getPjaxContainer()); // Make Field Editable
                                return $result;
                            };
                        }

                    } else {
                        if (!key_exists('value', $column)) {
                            $column['value'] =  function ($model, $key, $index, $column1) {
                                $showType = !FHtml::isInArray($column1->format, ['raw']) ? $column1->format : FHtml::getShowType($model, $column1->attribute);
                                $field = $column1->attribute;
                                $value = FHtml::getFieldValue($model, $field);
                                $result = FHtml::showContent($value, $showType, $this->object_type, $field, '', '', '', '');
                                return $result;
                            };
                        }
                    }

                    $column['format'] = 'raw';
                }
                if (!empty($field) && (FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $this->object_type) || key_exists($this->object_type . '.' . $field, FHtml::LOOKUP) || StringHelper::endsWith($class, 'BooleanColumn') || FHtml::isInArray($field, FHtml::FIELDS_BOOLEAN, $this->object_type))) {
                    $column['hAlign'] = FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $this->object_type) ? 'center' : 'left';
                    $column['vAlign'] = 'middle';
                }

            } else if (in_array($editor, [FHtml::EDITOR_SWITCH, FHtml::EDITOR_BOOLEAN]) && !empty($field) && StringHelper::endsWith($class, 'BooleanColumn') || FHtml::isInArray($field, FHtml::FIELDS_BOOLEAN, $this->object_type)) {
                $column['hAlign'] = 'center';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';

                $column['filterType'] = GridView::FILTER_SELECT2;
                $column['filter'] = FHtml::getComboArray('', $this->object_type, $field, true, 'id', 'name');
                $column['filterWidgetOptions'] = [
                    'pluginOptions' => ['allowClear' => true],
                ];
                $column['class'] = FHtml::COLUMN_VIEW;
                if ($edit_type == FHtml::EDIT_TYPE_INLINE || $edit_type == FHtml::EDIT_TYPE_INPUT) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        $result = FHtml::showModelFieldValue($model, $field, '', '', false, $this->getPjaxContainer());
                        $result = FHtml::showBooleanEditable($result, FHtml::getFieldValue($model, $field), $field, $model->id, $this->object_type, true, $this->getPjaxContainer()); // Make Field Editable
                        return $result;
                    };
                    FHtml::registerEditorJS($field, $edit_type, $this->getPjaxContainer());

                }  else {
                    $column['class'] = FHtml::COLUMN_EDIT;

                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        $value = FHtml::getFieldValue($model, $field);

                        $result = FHtml::showActive($value, $field);
                        return $result;
                    };
                }

            } else if (in_array($editor, [FHtml::EDITOR_SELECT]) || (!empty($field) && FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $this->object_type)) ||key_exists('filter', $column) || $show_type == FHtml::SHOW_LABEL || key_exists($this->object_type . '.' . $field, FHtml::LOOKUP)) {
                $column['class'] = FHtml::COLUMN_VIEW;
                $column['hAlign'] = (!empty($field) && FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $this->object_type)) ? 'center' : 'left';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';
                $column['filterType'] = \kartik\grid\GridView::FILTER_SELECT2;
                $column['filter'] = FHtml::getComboArray('', $this->object_type, $field, true, 'id', 'name');

                $column['filterInputOptions'] = ['placeholder' => ''];
                $column['filterWidgetOptions'] = [
                    'pluginOptions' => ['allowClear' => true],
                ];

                if ($edit_type == FHtml::EDIT_TYPE_INLINE || $edit_type == FHtml::EDIT_TYPE_INPUT) {

                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        //2017/3/6
                        $result = FHtml::showModelFieldValue($model, $field, FHtml::SHOW_LABEL, '', true, $this->getPjaxContainer(), $this->getEditType());
                        return $result;
                    };

                    FHtml::registerEditorJS($field, $edit_type, $this->getPjaxContainer());
                }
            } else if (!empty($field) && !FHtml::isInArray($field, FHtml::FIELDS_IMAGES, $this->object_type)) {
                //echo "$field -> $edit_type -> " . $column['class'] . " || ";
                $column['class'] = FHtml::COLUMN_VIEW; //FHtml::getColumnClass(FHtml::currentController(), $column['attribute'], '');

                if (!key_exists('hAlign', $column))
                    $column['hAlign'] = 'left';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';

                if ($edit_type == FHtml::EDIT_TYPE_INLINE || $edit_type == FHtml::EDIT_TYPE_INPUT) {

                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        $result = FHtml::showModelFieldValue($model, $field, '', '', true, $this->getPjaxContainer(), $this->getEditType());
                        return $result;
                    };

                    FHtml::registerEditorJS($field, $edit_type, $this->getPjaxContainer());
                }
            } else if ($format == FHTml::SHOW_IMAGE || (!empty($field) && FHtml::isInArray($field, FHtml::FIELDS_IMAGES, $this->object_type))) {
                $column['class'] = FHtml::COLUMN_VIEW; //FHtml::getColumnClass(FHtml::currentController(), $column['attribute'], '');
                $column['hAlign'] = 'center';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';
                if (!key_exists('value', $column)) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $value = FHtml::getFieldValue($model, array_merge([$column1->attribute], ['image', 'thumbnail', 'banner', 'avatar']));
                        $result = FHtml::showImageThumbnail($value, FHtml::config('ADMIN_THUMBNAIL_WIDTH', '50'), FHtml::getUploadFolder(str_replace('_', '-', $this->object_type)));
                        return $result;
                    };
                }
            } else {
                //die;
            }

            //$class = FHtml::getFieldValue($column, 'class', '');
            if (StringHelper::endsWith($class, 'BooleanColumn') || FHtml::isInArray($attribute, FHtml::getFIELDS_GROUP())) {
                $bool_columns[] = $column;
            } else if (FHtml::isInArray($attribute, FHtml::FIELDS_IMAGES)) {
                $image_columns[] = $column;
            } else if (in_array($attribute, ['id']) || FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn'])) {
                $column['vAlign'] = 'middle';
                $column['hAlign'] = 'center';

                $first_columns[] = $column;
            } else if (StringHelper::endsWith($class, 'ActionColumn')) {
                if (key_exists('viewOptions', $column))
                    $column['viewOptions'] = array_merge($column['viewOptions'], ['label' => '<span class="glyphicon glyphicon-eye-open btn btn-xs btn-default"></span>']);
                else
                    $column['viewOptions'] = ['label' => '<span class="glyphicon glyphicon-eye-open btn btn-xs btn-default"></span>'];

                if (key_exists('updateOptions', $column))
                    $column['updateOptions'] = array_merge($column['updateOptions'], ['label' => '<span class="glyphicon glyphicon-pencil btn btn-xs btn-primary"></span>']);
                else
                    $column['updateOptions'] = ['label' => '<span class="glyphicon glyphicon-pencil btn btn-xs btn-primary"></span>'];

                if (key_exists('deleteOptions', $column))
                    $column['deleteOptions'] = array_merge($column['deleteOptions'], ['label' => '<span class="glyphicon glyphicon-trash btn btn-xs btn-danger"></span>']);
                else
                    $column['deleteOptions'] = ['label' => '<span class="glyphicon glyphicon-trash btn btn-xs btn-danger"></span>'];

                $action_columns[] = $column;
            } else if (FHtml::isInArray($attribute, ['sort_order', 'application_id'])) {
                //ignore those fields
            } else {

                $columns[] = $column;
            }

            if (FHtml::isInArray($column['class'], ['*DataColumn','*EditableColumn', '*BooleanColumn']) && !key_exists('value', $column)) {
                $column['value'] = function ($model, $key, $index, $column1) {
                    $value = $model->{$column1->attribute};
                    return $value;
                };
            }

            $this->columns[$i] = $column;
        }


        if ($this->columns_auto_arrange) {
            $columns = array_merge($image_columns, $columns);
            $columns = array_merge($first_columns, $columns);

            $columns = array_merge($columns, $bool_columns);
            $columns = array_merge($columns, $action_columns);

            $this->columns = $columns;
        }

        //2017/3/5
        if ($this->isPrint()) {
            $this->render_type = FHtml::RENDER_TYPE_CODE;
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;
            $columns = [];
            foreach ($this->columns as $i => $column) {
                if (FHtml::field_exists($column, 'class'))
                    $column['class'] = FHtml::COLUMN_VIEW;
                unset($column['editableOptions']);

                if (FHtml::field_exists($column, 'class') && FHtml::isInArray($column['class'], ['*DataColumn', '*BooleanColumn']) && (FHtml::field_exists($column, 'attribute') && !FHtml::isInArray($column['attribute'], FHtml::FIELDS_HIDDEN)))
                    $columns[] = $column;
            }
            $this->bordered = true;
            $this->condensed = true;
            $this->columns = $columns;
        }

        self::initColumnsParent(); // TODO: Change the autogenerated stub

        $columns = [];
        foreach ($this->columns as $i => $column) {
//            if ($this->form_enabled && FHtml::field_exists($column, 'attribute') && $column->group == true)
//                $column->groupedRow = false;

            if (FHtml::field_exists($column, 'attribute') && $column->attribute == 'id') {
                $column->width = '2%';
                $this->columns[$i] = $column;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::FIELDS_IMAGES, $this->object_type)) {
                $column->width = '5%';
                $this->columns[$i] = $column;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::FIELDS_BOOLEAN, $this->object_type)) {
                $column->width = '8%';
                $this->columns[$i] = $column;

                $columns[] = $column->attribute;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::getFIELDS_GROUP(), $this->object_type)) {
                $column->width = '10%';
                $this->columns[$i] = $column;

                $columns[] = $column->attribute;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, ['*color', '*icon'])) {
                $column->width = '5%';
                $this->columns[$i] = $column;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::FIELDS_PRICE, $this->object_type)) {
                $column->width = '10%';
                $this->columns[$i] = $column;

                $columns[] = $column->attribute;
            } else if (StringHelper::endsWith(get_class($column), 'ActionColumn')) {
                $column->header = '';
                $column->width = '8%';

                $this->columns[$i] = $column;
                $this->actionColumn = $column;
            }  else if (StringHelper::endsWith(get_class($column), 'SerialColumn')) {
                $column->width = '1%';
                $this->columns[$i] = $column;
            } else if (StringHelper::endsWith(get_class($column), 'CheckboxColumn')) {
                $column->width = '1%';
                $this->columns[$i] = $column;
            } else if (StringHelper::endsWith(get_class($column), 'ExpandRowColumn')) {
                $column->width = '1%';
                $this->columns[$i] = $column;
            } else {
                if (FHtml::field_exists($column, 'attribute'))
                {
                    $columns[] = $column->attribute;
                }
            }
        }

        $this->attributes = $columns;

        if ($this->filterEnabled !== true) { //turn off Filter bar
            $this->filterModel = null;
        } else {
            $this->form_field_template = '{object}[{column}]'; // both Filter/Search and Add form existed -> change naming method
        }
    }

    private function getLabelForColumn($arr, $return_array = true) {
        if (!key_exists('label', $arr))
            $arr['label'] = key_exists('attribute', $arr) ? FHtml::getFieldLabel($this->filterModel, $arr['attribute']) : '';
//        else
//            $arr['label'] = FHtml::t('common', $arr['label']);

        return $return_array ? $arr : $arr['label'];
    }

    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    protected function renderTableHeader1($content1 = '')
    {
        $cells = [];
        $cells1 = [];

        foreach ($this->columns as $column) {
            /* @var $column Column */
            $a = $column->renderHeaderCell();
            $a = self::renderCell($a);

            $cells1[] =  '<td>' . (FHtml::field_exists($column, 'attribute') ? FHtml::getFieldLabel($this->object_type, $column->attribute) : '') . '</td>' ;
            $cells[] = $a;
        }

        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);

        if ($this->filterPosition === self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition === self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead class='hidden-print'>\n" . $content . "\n</thead>" . Html::tag('tr', implode('', $cells1), ['class' => 'visible-print form-label']);
        ;
    }

    /**
     * Renders the table footer.
     * @return string the rendering result.
     */
    public function renderTableFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $a = $column->renderFooterCell();
            $a = self::renderCell($a);
            $cells[] = $a;
        }
        $content = Html::tag('tr', implode('', $cells), $this->footerRowOptions);
        if ($this->filterPosition === self::FILTER_POS_FOOTER) {
            $content .= $this->renderFilters();
        }

        return "<tfoot>\n" . $content . "\n</tfoot>";
    }

    public function renderTableRow($model, $key, $index)
    {
        $cells = [];

        //Hung: rewrite $key = $id; because if the array is re-arrange and re-index then $key is not $id any more
        $key = FHtml::getModelPrimaryKeyValue($model);

        /* @var $column Column */
        foreach ($this->columns as $column) {
            if (FHtml::field_exists($column, 'value') && is_string($column->value))
                $a = Html::tag('td', $column->value);
            else {
                $a = $column->renderDataCell($model, $key, $index);
            }
            $a = self::renderCell($a);
            $cells[] = $a;
        }

        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['id'] = $this->getPjaxContainer() . '_' . (is_array($key) ? json_encode($key) : (string) $key);

        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        $a = Html::tag('tr', implode('', $cells), $options);

        return $a;
    }

    protected function renderCell($a, $header = false, $style = "border:0.25pt solid gray; vertical-align:middle; font-size: 12pt; padding: 5px; mso-ignore: padding;") {
        if ($this->isPrint()) {
            if ($header)
                $style .= 'background:#eef1f5; font-weight: bold;';
            $a = str_replace('<td ', "<td style='$style' ", $a);
        }
        return $a;
    }

    /**
     * Renders the pager.
     * @return string the rendering result
     */
    public function renderPager()
    {
        $pagination = isset($this->dataProvider) ? $this->dataProvider->getPagination() : null;
        if (!isset($pagination) || $pagination === false || $this->getItemsTotalCount() <= 0) {
            return '';
        }
        /* @var $class LinkPager */
        $pager = $this->pager;

        if (!is_array($pager))
            return '';

        $class = ArrayHelper::remove($pager, 'class', LinkPager::className());
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();

        return "<div class='hidden-print'>" . $class::widget($pager) . "</div>";
    }

    protected function initToggleData()
    {
        if (!$this->toggleData) {
            return;
        }
        $defaultOptions = [
            'maxCount' => 10000,
            'minCount' => 500,
            'confirmMsg' => FHtml::t(
                'message',
                'There are {totalCount} records. Are you sure you want to display them all?'
            ),
            'all' => [
                'icon' => 'resize-full',
                'label' => FHtml::t('message', 'All'),
                'class' => 'btn btn-default',
                'title' => FHtml::t('kvgrid', 'Show all data')
            ],
            'page' => [
                'icon' => 'resize-small',
                'label' => FHtml::t('message', 'Page'),
                'class' => 'btn btn-default',
                'title' => FHtml::t('message', 'Show first page data')
            ],
        ];
        $this->toggleDataOptions = array_replace_recursive($defaultOptions, $this->toggleDataOptions);
        $tag = $this->_isShowAll ? 'page' : 'all';
        $options = $this->toggleDataOptions[$tag];
        $this->toggleDataOptions[$tag]['id'] = $this->_toggleButtonId;
        $icon = ArrayHelper::remove($this->toggleDataOptions[$tag], 'icon', '');
        $label = !isset($options['label']) ? $defaultOptions[$tag]['label'] : $options['label'];
        if (!empty($icon)) {
            $label = "<i class='glyphicon glyphicon-{$icon}'></i> " . $label;
        }
        $this->toggleDataOptions[$tag]['label'] = $label;
        if (!isset($this->toggleDataOptions[$tag]['title'])) {
            $this->toggleDataOptions[$tag]['title'] = $defaultOptions[$tag]['title'];
        }
        $this->toggleDataOptions[$tag]['data-pjax'] = $this->pjax ? "true" : false;
    }

    protected function registerAssets()
    {
        $view = $this->getView();
        $script = '';
        if ($this->bootstrap) {
            GridViewAsset::register($view);
        }
        //Dialog::widget($this->krajeeDialogSettings);
        $gridId = $this->options['id'];
        $NS = '.' . str_replace('-', '_', $gridId);
        if ($this->export !== false && is_array($this->export) && !empty($this->export)) {
            GridExportAsset::register($view);
            $target = ArrayHelper::getValue($this->export, 'target', self::TARGET_BLANK);
            $gridOpts = Json::encode(
                [
                    'gridId' => $gridId,
                    'target' => $target,
                    'messages' => $this->export['messages'],
                    'exportConversions' => $this->exportConversions,
                    //'showConfirmAlert' => ArrayHelper::getValue($this->export, 'showConfirmAlert', true),
                ]
            );
            $gridOptsVar = 'kvGridExp_' . hash('crc32', $gridOpts);
            $view->registerJs("var {$gridOptsVar}={$gridOpts};", View::POS_HEAD);
            foreach ($this->exportConfig as $format => $setting) {
                $id = "jQuery('#{$gridId} .export-{$format}')";
                $genOpts = Json::encode(
                    [
                        'filename' => $setting['filename'],
                        'showHeader' => $setting['showHeader'],
                        'showPageSummary' => $setting['showPageSummary'],
                        'showFooter' => $setting['showFooter'],
                    ]
                );
                $genOptsVar = 'kvGridExp_' . hash('crc32', $genOpts);
                $view->registerJs("var {$genOptsVar}={$genOpts};", View::POS_HEAD);
                $expOpts = Json::encode(
                    [
                        'dialogLib' => ArrayHelper::getValue($this->krajeeDialogSettings, 'libName', 'krajeeDialog'),
                        'gridOpts' => new JsExpression($gridOptsVar),
                        'genOpts' => new JsExpression($genOptsVar),
                        'alertMsg' => ArrayHelper::getValue($setting, 'alertMsg', false),
                        'config' => ArrayHelper::getValue($setting, 'config', []),
                    ]
                );
                $expOptsVar = 'kvGridExp_' . hash('crc32', $expOpts);
                $view->registerJs("var {$expOptsVar}={$expOpts};", View::POS_HEAD);
                $script .= "{$id}.gridexport({$expOptsVar});";
            }
        }
        $container = 'jQuery("#' . $this->containerOptions['id'] . '")';
        if ($this->resizableColumns) {
            $rcDefaults = [];
            if ($this->persistResize) {
                GridResizeStoreAsset::register($view);
            } else {
                $rcDefaults = ['store' => null];
            }
            $rcOptions = Json::encode(array_replace_recursive($rcDefaults, $this->resizableColumnsOptions));
            GridResizeColumnsAsset::register($view);
            $script .= "{$container}.resizableColumns('destroy').resizableColumns({$rcOptions});";
        }
        if ($this->floatHeader) {
            GridFloatHeadAsset::register($view);
            // fix floating header for IE browser when using group grid functionality
            $skipCss = '.kv-grid-group-row,.kv-group-header,.kv-group-footer'; // skip these CSS for IE
            $js = 'function($table){return $table.find("tbody tr:not(' . $skipCss . '):visible:first>*");}';
            $opts = [
                'floatTableClass' => 'kv-table-float',
                'floatContainerClass' => 'kv-thead-float',
                'getSizingRow' => new JsExpression($js),
            ];
            if ($this->floatOverflowContainer) {
                $opts['scrollContainer'] = new JsExpression("function(){return {$container};}");
            }
            $this->floatHeaderOptions = array_replace_recursive($opts, $this->floatHeaderOptions);
            $opts = Json::encode($this->floatHeaderOptions);
            $script .= "jQuery('#{$gridId} .kv-grid-table:first').floatThead({$opts});";
            // integrate resizeableColumns with floatThead
            if ($this->resizableColumns) {
                $script .= "{$container}.off('{$NS}').on('column:resize{$NS}', function(e){" .
                    "jQuery('#{$gridId} .kv-grid-table:nth-child(2)').floatThead('reflow');" .
                    '});';
            }
        }
        if ($this->perfectScrollbar) {
            GridPerfectScrollbarAsset::register($view);
            $script .= $container . '.perfectScrollbar(' . Json::encode($this->perfectScrollbarOptions) . ');';
        }
        $this->genToggleDataScript();
        $script .= $this->_toggleScript;
        $this->_gridClientFunc = 'kvGridInit_' . hash('crc32', $script);
        $this->options['data-krajee-grid'] = $this->_gridClientFunc;
        $view->registerJs("var {$this->_gridClientFunc}=function(){\n{$script}\n};\n{$this->_gridClientFunc}();");
    }

    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return \Yii::createObject([
            'class' => $this->dataColumnClass ? : FDataColumn::className(),
            'grid' => $this,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }
}
