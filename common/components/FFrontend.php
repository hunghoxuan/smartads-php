<?php

namespace common\components;

use applications\hotel\models\EcommerceProduct;
use applications\itc\models\CmsBlogs;
use backend\assets\CustomAsset;
use backend\models\ObjectCategory;
use backend\modules\cms\models\CmsContent;
use backend\modules\cms\models\CmsWidgets;
use backend\modules\system\models\SettingsMenu;
use common\widgets\FContact\FContact;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use Yii;
use yii\helpers\StringHelper;
use yii\web\View;


/**
 * Class FFrontend
 * @package common\components
 */
class FFrontend extends FApi
{
	//    const LIST_URL = '/{name}-c{category_id}/list';
	//    const VIEW_URL = '/{name}-p{id}-c{category_id}/view';
	const LIST_URL             = '/{name}-c{category_id}';
	const VIEW_URL             = '/{name}-p{id}-c{category_id}';
	const VIEW_DETAIL_URL      = '/{name}-p{id}';
	const PAGE_OBJECT_TYPE_ALIAS    = ['product' => 'ecommerce_product', 'blog' => 'cms_blogs'];
	const CONTENT_ABOUT        = 'cms_about';
	const CONTENT_TESTIMONIAL  = 'cms_testimonial';
	const CONTENT_PORTFOLIO    = 'cms_portfolio';
	const CONTENT_FEEDBACK     = 'cms_feedback';
	const CONTENT_SERVICE      = 'cms_service';
	const CONTENT_HELP         = 'cms_help';
	const CONTENT_FAQ          = 'cms_faq';
	const CONTENT_FOOTER       = 'FOOTER';
	const CONTENT_CONTACT      = 'CONTACT';
	const CONTENT_CONTACT_FORM = 'CONTACT_FORM';
	const CONTENT_PAGE_META    = 'CONTENT_PAGE_META';
	const CONTENT_HEADER       = 'HEADER';

	/**
	 * @var
	 */
	public $identityClass;

	/**
	 * @param $string
	 * @param $tagname
	 * @return mixed
	 */
	public static function findTags($string, $tagname) {
		$pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
		preg_match($pattern, $string, $matches);

		return $matches[1];
	}



	/**
	 * @param       $view
	 * @param       $content
	 * @param array $arr_replace
	 * @return mixed
	 */
	public static function contentPage($view, $content, $arr_replace = []) {
		//Get base url
		$asset   = CustomAsset::register($view);
		$app     = FHtml::currentApplicationFolder();
		$baseUrl = $asset->baseUrl . "/applications/{$app}/frontend/assets/";
        //$baseUrl = $asset->baseUrl . "/frontend/themes/default/assets/";

		$arr_replace = array_merge($arr_replace, [
			'href="images'      => 'href="' . $baseUrl . 'images',
			'href="css'         => 'href="' . $baseUrl . 'css',
			'src="images'       => 'src="' . $baseUrl . 'images',
			'url(\'images'      => 'url(\'' . $baseUrl . 'images',
			'src="js'           => 'src="' . $baseUrl . 'js',
			'<script></script>' => '<script>' . FHtml::registerEditorJS('content', FHtml::EDIT_TYPE_INLINE, 'crud-datatable-page-content-pjax') . '</script>',
			'var baseUrl;'      => 'var baseUrl =' . FHtml::createUrl('site/quote') . ';',
			'{{page.title}}'    => FHtml::currentCompanyName() . FHtml::settingCompanyDescription(),
			'{{page.favicon}}'  => FHtml::getCurrentFavicon('images/favicon.ico'),
		]);

		/** @var View $view */
		$view->registerJsFile($baseUrl . "/js/eModal.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
		$view->registerJsFile($baseUrl . "/js/init.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
		FHtml::registerEditorJS("");

		return FHtml::strReplace($content, $arr_replace);
	}

	/**
	 * @param $code
	 * @return CmsContent|\common\base\BaseDataObject|null
	 */
	public static function contentModel($code) {
		$params = ['code' => $code];
		$model  = CmsContent::findOne($params);

		if (!isset($model)) {
			$model            = CmsContent::createNew(false, $params);
			$model->is_active = true;
			$model->code      = $code;
			$model->save();
		}

		return $model;
	}

	/**
	 * @param $content_name
	 * @return CmsWidgets|\common\base\BaseDataObject|null
	 */
	public static function widgetModel($content_name) {
		$page_code = FHtml::currentPageCode();

		$params = ['page_code' => $page_code, 'name' => $content_name];
		$model  = CmsWidgets::findOne($params);
		if (!isset($model)) {
			$model            = CmsWidgets::createNew(false, $params);
			$model->is_active = true;
			$model->page_code = $page_code;
			$model->name      = $content_name;
			$model->save();
		}

		return $model;
	}

	/**
	 * @param       $code
	 * @param null  $default
	 * @param array $fields
	 * @param bool  $autoSave
	 * @return bool|int|mixed|null
	 */
	public static function contentItem($code, $default = null, $fields = [], $autoSave = false) {
		if (!isset($default)) {
			$default = $code;
			$code    = FHtml::currentPageCode() . '_' . md5($default);
		}
		elseif (is_array($default)) {
			$fields  = $default;
			$default = $code;
			$code    = FHtml::currentPageCode() . '_' . md5($default);
		}

		$model = self::contentModel($code);

		if ($autoSave) {
			$result = FHtml::getFieldValue($model, ['template_layout', 'content', 'title']);
			if (empty($result)) {
				$model->template_layout = $default;
				$model->save();
				$result = $default;
			}
		}
		else {
			$result = $default;
		}

		$result = self::showModelTemplate($model, $result, $fields);

		return $result;
	}

	/**
	 * @param        $content_name
	 * @param null   $content
	 * @param string $item_template
	 * @param string $row_template
	 * @param int    $items_limit
	 * @param int    $columns_in_row
	 * @param array  $fields
	 * @param bool   $autoSave
	 * @return bool|int|mixed|null
	 */
	public static function contentWidget($content_name, $content = null, $item_template = '', $row_template = '', $items_limit = -1, $columns_in_row = 0, $fields = [], $autoSave = false) {
		if (!isset($content)) {
			$content      = $content_name;
			$content_name = 'Content_' . md5($content);
		}
		elseif (is_array($content)) {
			$fields       = $content;
			$content      = $content_name;
			$content_name = 'Content_' . md5($content);
		}

		$model = self::widgetModel($content_name);

		if ($autoSave) {
			$result = FHtml::getFieldValue($model, ['content'], $content);
			if (strpos($content, '{{') > 0) {
				$model->item_layout = $content;
			}
			else {
				$model->content = $content;
			}

			if (empty($model->items)) {
				$model->items = FHtml::encode($fields);
			}

			if (empty($model->columns_count)) {
				$model->columns_count = $columns_in_row;
			}

			if (empty($model->items_count)) {
				$model->items_count = $items_limit;
			}

			if (empty($model->item_layout)) {
				$model->item_layout = $item_template;
			}

			if (empty($model->object_type)) {
				$model->object_type = $content_name;
			}

			$model->save();
		}
		else {
			$result = $content;
		}


		$result = self::showModelTemplate($model, $result, $fields);

		if (!empty($item_template)) {
			$widget_models_content = self::contentModels($content_name, $item_template, $row_template, $items_limit, $columns_in_row);
			$result                = str_replace('{{models}}', $widget_models_content, $result);
		}

		return $result;
	}

	/**
	 * @param        $object_type
	 * @param        $item_template
	 * @param string $rows_template
	 * @param int    $items_limit
	 * @param int    $columns_in_row
	 * @param array  $item_fields
	 * @return string
	 */
	public static function contentModels($object_type, $item_template, $rows_template = '{{items}}', $items_limit = -1, $columns_in_row = 0, $item_fields = []) {
		$items_filter = [];
		if (is_string($object_type)) {
			$items_filter = ['object_type' => $object_type];
			$items        = CmsContent::findAll($items_filter, 'sort_order asc, created_date desc', ($items_limit > 0 ? $items_limit : -1));

		}
		elseif (is_object($object_type) || (is_array($object_type) && !empty($object_type) && is_string($object_type[0]))) {
			$items       = $object_type;
			$object_type = FHtml::getTableName($items);
		}
		elseif (is_array($object_type) && !empty($object_type) && is_string($object_type[0])) {
			$items_filter = $object_type;
			$items        = CmsContent::findAll($items_filter, 'sort_order asc, created_date desc', ($items_limit > 0 ? $items_limit : -1));
			$object_type  = FHtml::getFieldValue($object_type, ['object_type', 'table']);
		}

		if ($items_limit == -1) {
			$items_limit = count($items);
		}

		$result     = '';
		$row_result = '';
		$row_i      = 0;

		for ($i = 0; $i < $items_limit; $i++) {
			$row_i += 1;
			if ($i >= count($items)) {
				$item                 = new CmsContent;
				$item->object_type    = $object_type;
				$item->code           = $object_type . '_' . FHtml::currentPageCode() . '_' . $i;
				$item->is_active      = 1;
				$item->application_id = FHtml::currentApplicationId();
				//echo "$i:$row_i:$object_type  ---- ";
				$item->save();
			}
			else {
				$item = $items[$i];
			}
			$row_result .= self::showModelTemplate($item, $item_template, $item_fields);
			$row_result = str_replace('{{active}}', $i == 0 ? 'active' : '', $row_result);
			if ($row_i >= $columns_in_row) {
				$row_i      = 0;
				$result     .= str_replace('{{items}}', $row_result, $rows_template);
				$row_result = '';
			}
		}

		return $result;
	}

	/**
	 * @param       $model
	 * @param       $template
	 * @param array $fields
	 * @return mixed|string
	 */
	public static function showModelTemplate($model, $template, $fields = []) {

		if (empty($template) && strpos($template, '{{') == 0) {
			return FHtml::showModelFieldValue($model, 'content');
		}

		$result = $template;

		if (empty($fields)) {
			$fields = (isset($model) && method_exists($model, 'getAttributes')) ? array_keys($model->getAttributes()) : ['title', 'name', 'description', 'linkurl', 'overview', 'content'];
		}

		foreach ($fields as $field => $field_value) {
			if (is_array($field_value)) {
				$field_value = FHtml::encode($field_value);
			}

			if (self::is_numeric($field) && isset($model)) {
				$field       = $field_value;
				$field_value = FHtml::showModelFieldValue($model, $field);

				$field_value_readonly = FHtml::getFieldValue($model, $field);
			}
			else {
				$field_value_readonly = $field_value;
			}

			$result = str_replace("{{{$field}}}", $field_value, $result);
			$result = str_replace("{{@$field}}", $field_value_readonly, $result);
		}

		if (isset($model)) {
			$result = str_replace("{{image_object}}", FHtml::showImage(FHtml::getFieldValue($model, 'image'), FHtml::getUploadFolder($model)), $result);
			$result = str_replace("{{image}}", FHtml::getImageUrl(FHtml::getFieldValue($model, 'image'), FHtml::getUploadFolder($model)), $result);
		}

		return $result;
	}

	/**
	 * @param string $controllerid
	 * @param string $action
	 * @param string $module
	 * @return array|null
	 */
	public static function getFrontendMenu($controllerid = '', $action = '', $module = '') {
		if (empty($module)) {
			$module = FHtml::currentApplicationId();
		}
		if (empty($controllerid)) {
			$controllerid = FHtml::currentController();
		}
		if (empty($action)) {
			$action = FHtml::currentAction();
		}

		$result = null;

		if (true || FRONTEND_MENU_FROM_MODULE) {
			if (is_string(FRONTEND_MENU_FROM_MODULE) && !empty(FRONTEND_MENU_FROM_MODULE)) {
				$module = FHtml::settingApplicationDefaultModule(FRONTEND_MENU_FROM_MODULE);
			}

			$helper = FHtml::getApplicationHelper($module);

			if (isset($helper) && method_exists($helper, 'getFrontendMenu')) {
				$result = $helper::getFrontendMenu($controllerid, $action);
			}
			else {
				$object = FHtml::getModuleObject($module, FRONTEND);
				if (isset($object) && method_exists($object, 'getFrontendMenu')) {
					$result = $object::getFrontendMenu($controllerid, $action);
				}
				else {
					$result = \frontend\modules\cms\Cms::getFrontendMenu($controllerid, $action);
				}
			}
		}

		if (FRONTEND_MENU_FROM_DB) {
			$group = FRONTEND;
			$menu  = null;
			//Get from database
			$menuList   = FHtml::getModels('settings_menu', ['is_active' => 1, 'group' => $group], 'sort_order', -1, 1, true);
			$moduleMenu = [];

			if (isset($menuList) && !empty($menuList) && !is_string($menuList)) {
				$result1    = [];
				$result2    = [];
				$moduleList = [];
				$controller = $controllerid;
				foreach ($menuList as $menuItem) {
					$child = null;
					$type  = '';
					if ($menuItem->display_type == SettingsMenu::DISPLAY_TYPE_MEGA) {
						$type  = 'mega-v5';
						$child = self::getCategoryItemMenu($menuItem->object_type, $menuItem->url . '/list', $menuItem->url . '/view', 4);
					}
					elseif ($menuItem->display_type == SettingsMenu::DISPLAY_TYPE_TREE) {
						$type  = 'tree';
						$child = self::getCategoryMenu($controllerid, $action, $menuItem->object_type, $menuItem->url);
					}
					else {
						$type = 'single';
					}


					$moduleMenu[] = [
						'name'     => FHtml::t('common', $menuItem->name),
						'url'      => strpos($menuItem->url, 'http') === false ? FHtml::createUrl($menuItem->url) : $menuItem->url,
						'active'   => $controller == $menuItem->url,
						'visible'  => true || FHtml::isInRoles($menuItem->role),
						'type'     => $type,
						'children' => $child,
						'icon'     => $menuItem->icon
					];
				}

			}

			if (is_array($moduleMenu) && !empty($moduleMenu)) {
				foreach ($moduleMenu as $menu_item) {
					$result[] = $menu_item;
				}
			}
		}

		return $result;
	}

	/**
	 * @param $name
	 * @param $array
	 * @return bool
	 */
	public static function checkHiddenField($name, $array) {
		foreach ($array as $item) {
			if (strpos($item, '*') == 0) {
				if (strpos($name, trim($item, '*')) !== false) {
					return true;
				}
			}
			else {
				if ($name == $item) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param $item
	 */
	public static function displayTreeMenu($item) {
		echo '<ul class="dropdown-menu">';

		foreach ($item as $children) {

			echo '<li class="';
			echo isset($children['children']) ? 'dropdown-submenu ' : '';
			echo (isset($children['active']) AND $children['active']) ? 'active' : '';
			echo '">';

			echo '<a href="';
			echo isset($children['url']) ? $children['url'] : 'javascript:void(0);';
			echo '">';

			echo Html::decode($children['label']);

			echo '</a>';

			if (isset($children['children']) AND is_array($children['children']) AND count($children['children']) != 0) {
				self::displayTreeMenu($children['children']);
			}
			echo '</li>';
		}
		echo '</ul>';

	}

	/**
	 * @param $item
	 * @return string
	 */
	public static function displayMegaMenuDefault($item) {
		$count = count($item);
		if (empty($count)) {
			return '';
		}
		$size = floor(12 / $count);
		if (empty($count)) {
			return '';
		}
		foreach ($item as $children) {
			echo '<div class="col-md-' . $size . ' equal-height-in">';
			echo '<ul class="list-unstyled equal-height-list">';

			echo '<li><h3>' . $children['label'] . '</h3></li>';
			if (isset($children['children']) AND is_array($children['children']) AND count($children['children']) != 0) {
				foreach ($children['children'] as $child) {
					$active = (isset($child['active']) AND $child['active']) ? ' class="active"' : '';
					echo '<li' . $active . '>';
					echo '<a href = "' . $child['url'] . '" >';
					echo '<i class="' . $child['icon'] . '" ></i >';
					echo Html::decode($child['label']);
					echo '</a >';
					echo '</li>';
				}
			}
			echo '</ul>';
			echo '</div>';
		}
	}

	/**
	 * @param $item
	 * @return string|void
	 */
	public static function displayMegaMenuV5($item) {
		$count = count($item);
		if (empty($count)) {
			return;
		}
		$size = floor(12 / $count);
		if (empty($count)) {
			return '';
		}
		foreach ($item as $children) {
			echo '<div class="col-md-' . $size . ' col-sm-6">';

			foreach ($children as $little_children) {

				echo '<h3 class="mega-menu-heading"><a href="' . $little_children['url'] . '">' . $little_children['label'] . '</a></h3>';

				echo '<ul class="list-unstyled style-list">';
				if (isset($little_children['children']) AND is_array($little_children['children']) AND count($little_children['children']) != 0) {
					foreach ($little_children['children'] as $child) {
						$active = (isset($child['active']) AND $child['active']) ? ' class="active"' : '';
						echo '<li' . $active . '>';
						echo '<a href = "' . $child['url'] . '" >';
						echo Html::decode($child['label']);
						echo '</a >';
						echo '</li>';
					}
				}
				echo '</ul>';
			}
			echo '</div>';
		}
	}

	/**
	 * @param $item
	 * @return string
	 */
	public static function displayMegaMenuV8($item) {
		$count = count($item);
		if (empty($count)) {
			return '';
		}

		$size = floor(12 / $count);
		if (empty($count)) {
			return '';
		}
		foreach ($item as $children) {
			echo '<div class="col-md-' . $size . ' md-margin-bottom-30">';
			foreach ($children as $little_children) {
				echo '<h2><a href="' . $little_children['url'] . '">' . $little_children['label'] . '</a></h2>';
				echo '<ul class="dropdown-link-list">';
				if (isset($little_children['children']) AND is_array($little_children['children']) AND count($little_children['children']) != 0) {
					foreach ($little_children['children'] as $child) {
						$active = (isset($child['active']) AND $child['active']) ? ' class="active"' : '';
						echo '<li' . $active . '>';
						echo '<a href = "' . $child['url'] . '" >';
						echo Html::decode($child['label']);
						echo '</a >';
						echo '</li>';
					}
				}
				echo '</ul>';
			}
			echo '</div>';
		}
	}


	/**
	 * @param $item
	 * @param $layout
	 */
	public static function displayMegaMenuV8Mix($item, $layout) {
		$icon = 'fa fa-volume-up';

		if ($layout == 'cii') {
			$size0 = 2;
			$size1 = 5;
			$size2 = 5;
		}
		elseif ($layout == 'cbi') {
			$size0 = 3;
			$size1 = 5;
			$size2 = 4;
		}
		else {
			$size0 = 4;
			$size1 = 4;
			$size2 = 4;
		}

		if (strpos($layout, 'c') !== false) {
			echo '<div class="col-md-' . $size0 . ' md-margin-bottom-30">';
			echo '<ul class="dropdown-link-list">';
			if (isset($item['left-content']) && count($item['left-content']) != 0) {
				$items = $item['left-content'];
				foreach ($items as $child) {
					$active = (isset($child['active']) AND $child['active']) ? ' class="active"' : '';
					echo '<li' . $active . '>';
					echo '<a href = "' . $child['url'] . '" >';
					echo Html::decode($child['label']);
					echo '</a >';
					echo '</li>';
				}
			}
			echo '</ul>';
			echo '</div>';
		}

		if ($layout == 'bii') {
			$first = $item['right-content'][0];
			echo '<div class="col-md-' . $size0 . ' md-margin-bottom-30">';
			echo '<div class="blog-grid">';
			if (isset($first)) {
				echo '<a href="' . $first['url'] . '">';
				echo '<img class="img-responsive" src="' . $first['image'] . '" alt="">';
				echo '</a>';
				echo '<h3 class="blog-grid-title-sm">';
				echo '<a href="' . $first['url'] . '">' . Html::decode($first['label']) . '</a>';
				echo '</h3>';
				if ($first['object_type'] == 'music_song') {
					echo '<div class="blog-date-time">' . $first['release_date'] . '</div>';
					echo strlen($first['singer']) != 0 ? '<h4>' . $first['singer'] . '</h4>' : '';
				}
			}
			echo '</div>';
			echo '</div>';
		}


		$array1 = array();
		$array2 = array();

		if (isset($item['right-content']) && count($item['right-content']) != 0) {
			$items2 = $item['right-content'];
			if ($layout == 'cbi') {
				$array1 = $items2[0];
				array_shift($items2);
				$array2 = $items2;
			}
			elseif ($layout == 'bii') {
				array_shift($items2);
				list($array1, $array2) = array_chunk($items2, ceil(count($items2) / 2));
			}
			else {
				list($array1, $array2) = array_chunk($items2, ceil(count($items2) / 2));
			}
		}


		if (count($array1) != 0) {
			echo '<div class="col-md-' . $size1 . ' md-margin-bottom-30">';
			if ($layout == 'cbi') {
				echo '<div class="blog-grid">';
				if (isset($array1)) {
					echo '<a href="' . $array1['url'] . '">';
					echo '<img class="img-responsive" src="' . $array1['image'] . '" alt="">';
					echo '</a>';
					echo '<h3 class="blog-grid-title-sm">';
					echo '<a href="' . $array1['url'] . '">' . Html::decode($array1['label']) . '</a>';
					echo '</h3>';

				}
				echo '</div>';
			}
			else {
				foreach ($array1 as $child1) {
					echo '<div class="blog-thumb  margin-bottom-20">';
					echo '<div class="blog-thumb-hover">';
					echo '<img src="' . $child1['image'] . '" alt="">';
					echo '<a class="hover-grad" href="' . $child1['url'] . '"><i class="' . $icon . '"></i></a>';
					echo '</div>';
					echo '<div class="blog-thumb-desc">';
					echo '<h3><a href="' . $child1['url'] . '">' . Html::decode($child1['label']) . '</a></h3>';
					echo ' <ul class="blog-thumb-info">';

					echo '</ul>';
					echo ' </div>';
					echo '</div>';
				}
			}

			echo '</div>';
		}


		if (count($array2) != 0) {
			echo '<div class="col-md-' . $size2 . '">';
			foreach ($array2 as $child2) {
				echo '<div class="blog-thumb  margin-bottom-20">';
				echo '<div class="blog-thumb-hover">';
				echo '<img src="' . $child2['image'] . '" alt="">';
				echo '<a class="hover-grad" href="' . $child2['url'] . '"><i class="fa fa-volume-up"></i></a>';
				echo '</div>';
				echo '<div class="blog-thumb-desc">';
				echo '<h3><a href="' . $child2['url'] . '">' . Html::decode($child2['label']) . '</a></h3>';
				echo ' <ul class="blog-thumb-info">';

				echo '</ul>';
				echo ' </div>';
				echo '</div>';
			}
			echo '</div>';
		}

	}

	public static function getObjectTypeAlias($object_type = '', $map = self::PAGE_OBJECT_TYPE_ALIAS) {
	    if (empty($map))
	        $map = self::PAGE_OBJECT_TYPE_ALIAS;

	    if (!empty($object_type) && is_array($map) && key_exists($object_type, $map))
	        return $map[$object_type];

	    return $object_type;
    }

	/**
	 * @param     $object_type
	 * @param     $list_url
	 * @param     $detail_url
	 * @param int $column_count
	 * @return array
	 */
	public static function getCategoryItemMenu($object_type, $list_url, $detail_url, $column_count = 4) {
	    $object_type1 = static::getObjectTypeAlias($object_type);
		$categories = FFrontend::getCategoriesList($object_type1);
		$total      = count($categories);

		$total_one_column = floor($total / $column_count);
		if ($total_one_column == 0) {
			$total_one_column = 1;
		}

		$result = array();

		/* @var $category ObjectCategory */

		foreach ($categories as $category) {
			$children  = array();
			$modelList = FHtml::getModelsList($object_type, ['category_id' => $category->id])->models;
			foreach ($modelList as $item) {

				$children[] = array(
					'label' => $item->name,
					'url'   => FHtml::createUrl([$detail_url, 'id' => $item->id]),
				);
			}
			$result[] = array(
				'label'    => $category->name,
				'url'      => FHtml::createUrl([$list_url, 'category_id' => $category->id]),
				'children' => $children
			);

		}

		$new = array_chunk($result, $total_one_column);

		$result = array();

		foreach ($new as $key => $value) {
			if ($key < $column_count) {
				$result[$key] = $value;
			}
			else {
				$result[$key % $column_count] = array_merge($result[$key % $column_count], $value);
			}
		}

		return $result;
	}

	/**
	 * @param $controller
	 * @param $action
	 * @param $object_type
	 * @param $list_url
	 * @return array
	 */
	public static function getCategoryMenu($controller, $action, $object_type, $list_url) {
		$objects = FFrontend::getCategoriesList($object_type);
		$result  = array();
		foreach ($objects as $item) {
			$result[] = array(
				'label' => $item->name,
				'url'   => FHtml::createUrl($list_url, ['category_id' => $item->id]),
			);
		}

		return $result;
	}

	/**
	 * @param array $menuArrays
	 * @return array
	 */
	public static function getArrayItemMenu($menuArrays = []) {
		foreach ($menuArrays as $label => $url) {
			$children = array();

			$result[] = array(
				'label'    => FHtml::t('common', $label),
				'url'      => FHtml::createUrl($url),
				'children' => null
			);

		}

		return $result;
	}

	/**
	 * @param        $object_type
	 * @param string $linkurl
	 * @param array  $params
	 * @param string $orderby
	 * @param string $name_field
	 * @param string $id_field
	 * @return array|null
	 */
	public static function getModelsItemMenu($object_type, $linkurl = '', $params = [], $orderby = '', $name_field = 'name', $id_field = 'id') {
		$models   = FHtml::getModels($object_type, $params, $orderby);
		$menuList = [];

		if (empty($models)) {
			return null;
		}

		foreach ($models as $item) {
			$menuList = array_merge($menuList, [FHtml::getFieldValue($item, $name_field) => str_replace('{' . $id_field . '}', FHtml::getFieldValue($item, $id_field), $linkurl)]);
		}

		return self::getArrayItemMenu($menuList);
	}

	/**
	 * @param string $object_type
	 * @param string $controllerURL
	 * @param string $subItemsField
	 * @param int    $column_count
	 * @return array
	 */
	public static function getMegaContentV5($object_type = 'product', $controllerURL = 'product', $subItemsField = 'products', $column_count = 4) {
		/* @var $category \backend\models\ObjectCategory */
		$categories = FHtml::getCategoriesByType($object_type);

		$total = count($categories);

		$total_one_column = floor($total / $column_count);

		$result = array();
		foreach ($categories as $category) {

			$children = array();
			$items    = FHtml::getFieldValue($category, $subItemsField);
			if (is_array($items)) {
				foreach ($items as $product) {
					$children[] = array(
						'label' => FHtml::getFieldValue($product, ['name', 'title']),
						'url'   => FHtml::createUrl(['/' . $controllerURL, 'id' => FHtml::getFieldValue($product, ['name', 'id'])]),
					);
				}
			}
			$result[] = array(
				'label'    => $category->name,
				'url'      => FHtml::createUrl(['/' . $controllerURL, 'category_id' => $category->id]),
				'children' => $children
			);

		}

		if (empty($result)) {
			return $result;
		}

		$new = array_chunk($result, $total_one_column);

		$result = array();

		foreach ($new as $key => $value) {
			if ($key < $column_count) {
				$result[$key] = $value;
			}
			else {
				$result[$key % $column_count] = array_merge($result[$key % $column_count], $value);
			}
		}

		return $result;
	}

	/**
	 * @param        $controller
	 * @param        $action
	 * @param string $object_type
	 * @param string $controllerURL
	 * @param int    $column_count
	 * @return array
	 */
	public static function getMegaContentV8($controller, $action, $object_type = 'product', $controllerURL = 'category', $column_count = 4) {
		$pathInfo = Yii::$app->request->pathInfo;

		if (strlen($pathInfo) != 0) {
			$pathInfo_array = explode('/', Yii::$app->request->pathInfo);
			$controller     = $pathInfo_array[0];
			$action         = $pathInfo_array[1];
		}

		$params_id = FHtml::getRequestParam('id');
		/* @var $category \backend\models\ObjectCategory */
		$queryParams = empty($object_type) ? '' : ' AND object_type = "' . $object_type . '""';
		$categories  = ObjectCategory::find()->where('parent_id = 0 AND is_active = 1' . $queryParams)->all();

		$objects = ObjectCategory::find()->where('is_active = 1' . $queryParams)->all();

		$total = count($categories);
		if ($total <= $column_count) {
			$total_one_column = 1;
		}
		else {
			$total_one_column = floor($total / $column_count - 1);
		}

		$result = array();
		foreach ($categories as $category) {

			$result[] = array(
				'label'    => $category->name,
				'url'      => FHtml::createUrl(['/' . $controllerURL, 'id' => $category->id]),
				'active'   => $controller == \Globals::TABLE_CATEGORIES && $action == 'view' && $params_id == $category->id,
				'children' => self::getChildrenOfCategory($category, $objects, $controller, $action, $params_id),
			);
		}

		$new = array_chunk($result, $total_one_column);

		$result = array();

		foreach ($new as $key => $value) {
			if ($key < $column_count) {
				$result[$key] = $value;
			}
			else {
				$result[$key % $column_count] = array_merge($result[$key % $column_count], $value);
			}
		}

		return $result;
	}

	/**
	 * @param string $type
	 * @param string $object_type
	 * @param string $controllerURL
	 * @param string $condition
	 * @return array
	 */
	public static function getMegaContentV8Mix($type = 'cii', $object_type = 'product', $controllerURL = 'product', $condition = '') //$type = cii, cbi, bii c: category, b:big item, i: normal item
	{
		$left_content = array();
		if (strpos($type, 'c') !== false) {
			$main_categories = ObjectCategory::find()->where('parent_id = 0 AND is_active = 1')->all();
			foreach ($main_categories as $category) {
				$left_content[] = array(
					'label' => $category->name,
					'url'   => FHtml::createUrl(['/' . $controllerURL, 'category_id' => $category->id]),
				);
			}
		}

		$right_content = array();
		$items         = [];

		if ($condition == 'top') {
			$items = FHtml::getModels($object_type, ['is_top = 1'], 'count_view DESC');
		}

		foreach ($items as $song) {
			$right_content[] = array(
				'label'        => $song->name,
				'url'          => FHtml::createUrl(['/' . $controllerURL, 'id' => $song->id]),
				'image'        => FHtml::getImageUrl(FHtml::getFieldValue($song, ['thumbnail', 'image']), $object_type),
				'created_date' => FHtml::getFieldValue($song, ['created_date', 'release_date']),
				'description'  => FHtml::getFieldValue($song, ['overview', 'description']),
				'object_type'  => $object_type
			);
		}

		$result = array(
			'left-content'  => $left_content,
			'right-content' => $right_content
		);

		return $result;

	}

	/**
	 * @param $controller
	 * @param $action
	 * @return array
	 */
	public static function getTreeContentByCategory($controller, $action) {
		$pathInfo = Yii::$app->request->pathInfo;

		if (strlen($pathInfo) != 0) {
			$pathInfo_array = explode('/', Yii::$app->request->pathInfo);
			$controller     = $pathInfo_array[0];
			$action         = $pathInfo_array[1];
		}

		$params_id = FHtml::getRequestParam('id');

		$objects = ObjectCategory::find()->where('is_active = 1')->all();
		/* @var $item \backend\models\ObjectCategory */

		$result = array();
		foreach ($objects as $item) {
			if ($item->parent_id == 0) {

				$menu_item = array(
					'label'  => $item->name,
					'active' => $controller == \Globals::TABLE_CATEGORIES && $action == 'view' && $params_id == $item->id,
					'url'    => FHtml::createUrl(['/category/view', 'id' => $item->id]),
				);
				$check     = self::getChildrenOfCategory($item, $objects, $controller, $action, $params_id);

				if (count($check) != 0) {
					$menu_item['children'] = $check;
				}

				$result[] = $menu_item;
			}
		}

		return $result;
	}

	/**
	 * @param $item
	 * @param $objects
	 * @param $controller
	 * @param $action
	 * @param $params_id
	 * @return array
	 */
	public static function getChildrenOfCategory($item, $objects, $controller, $action, $params_id) {
		$result = array();
		$value  = $item->id;
		$keys   = array_values(array_filter($objects, function($arrayValue) use ($value) {
			return isset($arrayValue['parent_id']) && $arrayValue['parent_id'] == $value;
		}));
		foreach ($keys as $child) {
			$menu_item = array(
				'label'  => $child->name,
				'active' => $controller == \Globals::TABLE_CATEGORIES && $action == 'view' && $params_id == $child->id,
				'url'    => FHtml::createUrl(['/category/view', 'id' => $child->id]),
			);
			$check     = self::getChildrenOfCategory($child, $objects, $controller, $action, $params_id);

			if (count($check) != 0) {
				$menu_item['children'] = $check;
			}

			$result[] = $menu_item;
		}

		return $result;
	}

	/**
	 * @param        $object_type
	 * @param string $linkurl
	 * @param array  $params
	 * @param string $orderby
	 * @param int    $limit
	 * @return array
	 */
	public static function getModelsChildrenMenu($object_type, $linkurl = '', $params = [], $orderby = '', $limit = 20) {
		$models = FHtml::getModels($object_type, $params, $orderby, $limit);

		if (empty($linkurl)) {
			$linkurl = $object_type;
		}

		$menu_children = array();
		foreach ($models as $model) {
			$name            = FHtml::getFieldValue($model, ['name', 'title']);
			$category_id     = FHtml::getFieldValue($model, 'category_id', '0');
			$menu_children[] = [
				'name' => $name,
				'url'  => self::createViewUrl($linkurl, ['name' => $name, 'id' => FHtml::getFieldValue($model, 'id'), 'category_id' => $category_id])
			];
		}

		return $menu_children;
	}

	/**
	 * @param        $object_type
	 * @param string $linkurl
	 * @param array  $params
	 * @param string $orderby
	 * @return array
	 */
	public static function getCategoriesChildrenMenu($object_type, $linkurl = '', $params = [], $orderby = '') {
	    $object_type1 = static::getObjectTypeAlias($object_type);
		if (empty($linkurl)) {
			$linkurl = $object_type;
		}

		if (empty($params)) {
			$params = $object_type1;
		}

		$models        = ArrayHelper::map(static::getCategoriesList($params), 'id', 'name'); //ArrayHelper::map(ObjectCategory::findAll($params), 'id', 'name');

		$menu_children = array();

		foreach ($models as $key => $value) {
			$menu_children[$key] = [
				'name' => $value,
				'url'  => self::createListUrl($linkurl, ['category_id' => $key, 'name' => $value])
			];
		}

		return $menu_children;
	}

	public static function getCmsCategoriesChildrenMenu($object_type = 'blog', $linkurl = '', $params = [], $orderby = '') {
        if (empty($object_type))
            $object_type = CmsBlogs::tableName();

        return static::getCategoriesChildrenMenu($object_type);
    }

    public static function getProductCategoriesChildrenMenu($object_type = 'product', $linkurl = '', $params = [], $orderby = '') {
	    if (empty($object_type))
	        $object_type = EcommerceProduct::tableName();

        return static::getCategoriesChildrenMenu($object_type);
    }

	/**
	 * @param       $controller
	 * @param null  $model
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createViewUrl($controller, $model = null, $params = []) {
        $zone = FHtml::currentZone();
        if ($zone == BACKEND) {
            if (is_array($model))
                $params = $model;
            return FHtml::createUrl($controller, $params);
        }

		$view_url = FUrlManager::VIEW_URL;

		if (empty($model)) {
			return $controller . $view_url;
		}
		$name        = FHtml::getFieldValue($model, ['slug', 'name', 'title']);
		$id          = FHtml::getFieldValue($model, ['id']);
		$category_id = FHtml::getFieldValue($model, ['category_id']);

        if (strpos($category_id, ',') !== false) {
            $arr = explode(',', trim($category_id, ","));
            foreach ($arr as $arr_item) {
                if (!empty($arr_item)) {
                    $category_id = $arr_item;
                    break;
                }
            }
        }
        if (!is_numeric($category_id))
            $category_id = 0;

		return FHtml::createUrl($controller . $view_url, array_merge(['id' => $id, 'name' => $name, 'category_id' => $category_id], $params));
	}

	/**
	 * @param       $controller
	 * @param null  $model
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createViewDetailUrl($controller, $model = null, $params = []) {
		if (empty($model)) {
			return $controller . self::VIEW_URL;
		}
		$name = FHtml::getFieldValue($model, ['slug', 'name', 'title']);
		$id   = FHtml::getFieldValue($model, ['id']);

		return FHtml::createUrl(self::VIEW_DETAIL_URL, array_merge(['id' => $id, 'name' => $name], $params));
	}

	/**
	 * @param       $controller
	 * @param null  $model
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createListUrl($controller = '', $model = null, $params = []) {
	    $zone = FHtml::currentZone();
	    if ($zone == BACKEND) {
	        if (is_array($model))
	            $params = $model;
	        return FHtml::createUrl($controller, $params);
        }
		$list_url = FUrlManager::LIST_URL;
		if (empty($controller))
		    $controller = FHtml::currentController();

		if (empty($model)) {
		    return FHtml::createUrl($controller);
		}

		$name        = FHtml::getFieldValue($model, ['slug', 'name', 'title']);
		$category_id = FHtml::getFieldValue($model, ['category_id', 'id']);

		return FHtml::createUrl($controller . $list_url, array_merge(['category_id' => $category_id, 'name' => $name], $params));
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createHomeUrl($params = []) {
		return FHtml::createUrl('/home', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createAboutUrl($params = []) {
		return FHtml::createUrl('/about', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createServiceUrl($params = []) {
		return FHtml::createUrl('/service', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createFAQUrl($params = []) {
		return FHtml::createUrl('/faq', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createBlogsUrl($params = []) {
		return FHtml::createUrl('/blog', $params);
	}

	/**
	 * @param string $name
	 * @param int    $id
	 * @param string $prefix
	 * @param array  $params
	 * @return mixed|string
	 */
	public static function createProductUrl($name = '', $id = 0, $prefix = '', $params = []) {
		return FHtml::createUrl($prefix . FHtml::getURLFriendlyName($name) . '-' . $id, $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createContactUrl($params = []) {
		return FHtml::createUrl('/contact', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createLoginUrl($params = []) {
		return FHtml::createUrl('/login', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createCartViewUrl($params = []) {
		return FHtml::createUrl('/ecommerce/order/viewcart', $params);
	}

	/**
	 * @param array $params
	 * @return mixed|string
	 */
	public static function createCartBillUrl($params = []) {
		return FHtml::createUrl('/ecommerce/order/bill', $params);
	}

	/**
	 * @return string
	 */
	public static function createAdminUrl() {
		$base_url = FHtml::currentBaseURL();

		return "$base_url/admin";
	}

	/**
	 * @param      $widget_id
	 * @param      $page_code
	 * @param null $widget_object
	 */
	public static function getPageWidget($widget_id, $page_code, $widget_object = null) {
		$model = CmsWidgets::findOne(['page_code' => $page_code, 'name' => $widget_id]);
		if (isset($model)) {
			FModel::copyFieldValues($widget_object, [
				'display_type',
				'items_count',
				'items_data',
				'items_orderby',
				'item_layout',
				'item_style',
				'content',
				'is_active',
				'title',
				'overview',
				'color',
				'color_bg',
				'width_css',
				'background_css',
				'columns_count'
			], $model, false);
		}
		else {
			$model            = new CmsWidgets;
			$model->page_code = $page_code;
			$model->name      = $widget_id;
			FModel::copyFieldValues($model, [
				'display_type',
				'items_count',
				'items_data',
				'items_orderby',
				'item_layout',
				'item_style',
				'content',
				'is_active',
				'title',
				'overview',
				'color',
				'color_bg',
				'width_css',
				'background_css',
				'columns_count'
			], $widget_object);
			$model->is_active      = 1;
			$model->created_date   = FHtml::Today();
			$model->created_user   = FHtml::currentUserId();
			$model->application_id = FHtml::currentApplicationCode();
			$model->save();
		}
	}

	/**
	 * @param       $object_type
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param array $displayFields
	 * @return null
	 */
	public static function getViewModels($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $displayFields = []) {
		$list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached);

		return isset($list) ? $list->viewModels : null;
	}

	// Get Articles

	/**
	 * @param        $object_type
	 * @param array  $search_params
	 * @param array  $order_by
	 * @param int    $page_size
	 * @param int    $page
	 * @param bool   $isCached
	 * @param string $folder
	 * @param array  $displayFields
	 * @return null
	 */
	public static function getViewModelsForAPI($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $displayFields = []) {
		$list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached);

		return isset($list) ? FHtml::prepareDataForAPI($list->viewModels, $folder, $displayFields) : null;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getBlogsModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->models;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return \common\models\BaseDataList
	 */
	public static function getBlogsList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getModelsList(self::TABLE_BLOGS, $search_params, $order_by, $page_size, $page, $isCached, $limit);
	}

	// Get Articles

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getBlogsViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getProductsModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getProductsList($search_params, $order_by, $page_size, $page, $isCached)->models;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return \common\models\BaseDataList
	 */
	public static function getProductsList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getModelsList(self::TABLE_PRODUCT, $search_params, $order_by, $page_size, $page, $isCached, $limit);
	}

	//HungHX: 20160801

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getProducts($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getProductsList($search_params, $order_by, $page_size, $page, $isCached)->models;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getProductsViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getProductsList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return \common\models\BaseDataList
	 */
	public static function getAboutList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getModelsList(self::TABLE_ABOUT, $search_params, $order_by, $page_size, $page, $isCached, $limit);
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getAboutModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->models;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getAboutViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return mixed
	 */
	public static function getArticleModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->models;
	}

	// Increase Field Values (used for updating statistic values)

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return \common\models\BaseDataList
	 */
	public static function getArticleList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getModelsList(self::TABLE_ARTICLE, $search_params, $order_by, $page_size, $page, $isCached, $limit);
	}

	// Assign value to model field

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getArticleViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
	}

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return \common\models\BaseDataList
	 */
	public static function getPromotionList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getModelsList(self::TABLE_PROMOTION, $search_params, $order_by, $page_size, $page, $isCached, $limit);
	}

	//HungHX: 20160814

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @param int   $limit
	 * @return mixed
	 */
	public static function getPromotionModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0) {
		return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->models;
	}

	//HungHX: 20160814

	/**
	 * @param array $search_params
	 * @param array $order_by
	 * @param int   $page_size
	 * @param int   $page
	 * @param bool  $isCached
	 * @return mixed
	 */
	public static function getPromotionViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false) {
		return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
	}

    public static function getCategoriesList($object_type = '', $params = [], $isCached = false)
    {
        return self::getCategoriesByType($object_type, $params, $isCached);
    }

    public static function getCategoriesByType($object_type = '', $params = [], $isCached = false)
    {
        return self::getCategories($object_type, $params, $isCached);
    }

    // getRelatedModels('album', 1, 'song'); getRelatedModels('product', 1, 'galleries');
    public static function getCategories($object_type = '', $object_id = -1, $isCached = false)
    {
        return FModel::getCategories($object_type, $object_id, $isCached);
    }

    public static function getProductCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_PRODUCT, $params, $isCached);
    }

    //2017/3/19

    public static function getNewsCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_BLOGS, $params, $isCached);
    }

    //2017/3/19

    public static function getGalleries($object_type, $object_id = false, $isCached = false)
    {
        if ($object_id === false) {// pass id or id array as first param
            if (is_string($object_type))
                $arr = explode(',', $object_type);
            else if (is_array($object_type))
                $arr = $object_type;
            $data = [];
            $model = self::createModel(self::TABLE_OBJECT_FILES);
            $data = $model::find()->where(['in', 'object_id', $arr])->all();

        } else {
            if ($isCached) {
                $data = self::getCachedData(self::TABLE_OBJECT_FILES, $object_type, $object_id);
                if (isset($data))
                    return $data;
            }
            $model = self::createModel(self::TABLE_OBJECT_FILES);
            $data = $model::findAll(['object_type' => $object_type, 'object_id' => $object_id, 'file_type' => 'image']);
            if ($isCached) {
                self::saveCachedData($data, self::TABLE_OBJECT_FILES, $object_type, $object_id);
            }
            return $data;
        }
    }

    public static function renderHead($default = '') {
        if (empty($default))
            $default = 'head.php';
        $view = $default;
        return FHtml::renderViewLayout($view);
    }

    public static function renderHeader($default = '') {
	    if (empty($default))
	        $default = 'header.php';
        $view = FHtml::getRequestParam('header') == 'no' ? '' : FHtml::settingWebsiteHeaderView($default);

        return FHtml::renderViewLayout($view);
    }

    public static function renderFoot($default = '') {
        if (empty($default))
            $default = 'foot.php';
        $view = $default;
        return FHtml::renderViewLayout($view);
    }

    public static function renderFooter($default = '') {
        if (empty($default))
            $default = 'footer.php';
        $view = FHtml::getRequestParam('footer') == 'no' ? '' : FHtml::settingWebsiteHeaderView($default);
        return FHtml::renderViewLayout($view);
    }

    public static function setPageMeta($title = '', $description = '', $image = '', $keyword = '', $url = '') {
        return static::addPageSEO($title, $description, $image, $keyword, $url);
    }

    public static function addPageSEO($title = '', $description = '', $image = '', $keyword = '', $url = '') {
        if (is_object($title)) {
            FHtml::currentView()->params['model'] = $title;
            return;
        }
        $key = FHtml::currentController() . '/' . FHtml::currentAction() . ':';
        FHtml::Session($key . 'page_description', $description);
        FHtml::Session($key . 'page_title', $title);
        FHtml::Session($key . 'page_image', $image);
        FHtml::Session($key . 'page_keyword', $image);
    }

    public static function getPageSEO($page = '', $title = '', $description = '', $keyword = '', $image = '', $checkSession = false) {
        $view_params       = isset(FHtml::currentView()->params) ? FHtml::currentView()->params : [];
        $model             = is_object($page) ? $page : (key_exists('model', $view_params) ? $view_params['model'] : null);

        $keyword    = isset($model) ? $model->getFieldValue(['keywords', 'tags', 'meta', 'seo']) : $keyword;
        $title = isset($model) ? $model->getFieldValue(['page_title', 'title', 'name']) : $title;
        $description = isset($model) ? $model->getFieldValue(['page_description', 'description', 'overview']) : $description;

        $key = FHtml::currentController() . '/' . FHtml::currentAction() . ':';

        if (empty($description) && $checkSession) {
            $description = FHtml::Session($key . 'page_description');
        }

        if (empty($title) && $checkSession) {
            $title = FHtml::Session($key . 'page_title');
        }

        if (empty($image) && $checkSession) {
            $image = FHtml::Session($key . 'page_image');
        }

        $title = (!empty($title) ? ($title . ' | ') : FHtml::settingPageTitle(BaseInflector::camel2words(FHtml::currentController())) . ' | ') . FHtml::settingWebsiteName(FHtml::currentCompanyName());
        $title = trim($title, " | .");

        $title = FContent::cleanHtml($title);
        $description = FContent::cleanHtml($description);

        if (empty($description))
            $description = $title;

        if (empty($image)) {
            $image = FHtml::settingPageImage();
        }

        $result   = [];
        $result[] = '<title>' . $title . '</title>';

        $keywords[] = FContent::cleanHtml($keyword);
        $keywords[] = FContent::cleanHtml(FHtml::settingPageKeywords());
        $keywords[] = FContent::cleanHtml(FHtml::settingWebsiteKeyWords());

        $keywords = array_unique($keywords);
        foreach ($keywords as $i => $item) {
            if (empty($item)) {
                unset($keywords[$i]);
            }
        }
        $description_arr[] = $description;
        $description_arr[] = FContent::cleanHtml(FHtml::settingPageDescription());
        $description_arr[] = FContent::cleanHtml(FHtml::settingWebsiteDescription());
        $description_arr   = array_unique($description_arr);
        foreach ($description_arr as $i => $item) {
            if (empty($item)) {
                unset($description_arr[$i]);
            }
        }

        $result[] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $result[] = '<meta name="description" content="' . implode('|', $description_arr) . '" />';
        $result[] = '<meta name="keywords" content="' . implode(', ', $keywords) . '" />';
        $result[] = '<meta name="robots" content="INDEX,FOLLOW" />';

        $result[] = '<meta property="og:url" content="' . FHtml::currentUrl() . '" />';
        $result[] = '<meta property="og:title" content="' . $title . '" />';
        $result[] = '<meta property="og:description" content="' . $description . '" />';
        $result[] = '<meta property="og:image" content="' . $image . '" />';
        $result[] = '<meta property="og:site_name" content="' . FHtml::settingCompanyName() . '" />';

        $result[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
        $result[] = '<meta name="msvalidate.01" content="2ADE18CF1FEB51AC6FEF2C2FC42594FA" />';

        $favicon  = FHtml::getCurrentFaviconUrl();
        if (!empty($favicon))
            $result[] = "<link id='favicon' rel='icon' type='image/png' href='$favicon' />";

        $google_site_verification = FHtml::settingWebsite('google_verification');
        if (!empty($google_site_verification))
            $result[] = "<meta name='google-site-verification' content='$google_site_verification'/>";

        $result = implode("\n\t", $result);

        return $result;
    }

    public static function renderGoogleAdwords($default = '805818623') {
	    $key = FHtml::setting('google_adwords_key', $default);
	    $result = '';
	    if (!empty($key)) {
            $result = \common\widgets\fsocialshare\FGoogleAdwords::widget(['google_adwords_key' => $key]);
        }

	    return $result;
    }

    public static function renderWebsiteScripts() {
        $result = FConfig::settingWebsiteScripts();
        return $result;
    }

    public static function renderWebsiteStyleSheets() {
        $result = FConfig::settingWebsiteStyleSheets();
        return $result;
    }

    public static function renderGoogleAnalytics($default = 'UA-102395244-1') {
        $key = FHtml::setting('google_analytic_key', $default);
        $result = '';
        if (!empty($key)) {
            $result = \common\widgets\fsocialshare\FGoogleAnalytic::widget(['google_analytic_key' => $key]);
        }

        return $result;
    }

    public static function renderContactsFooter($enabled = null) {
	    if (!isset($enabled))
	        $enabled = FConfig::setting('show_as_footer', true);
	    $result = '';
	    if ($enabled)
            $result = \common\widgets\fcontact\FContact::widget(['display_type' => 'contactfooter']);
        return $result;
    }

    public static function renderContactsButtons($enabled = null) {
        if (!isset($enabled))
            $enabled = FConfig::setting('show_as_buttons', true);
        $result = '';
        if ($enabled)
            $result = \common\widgets\fcontact\FContact::widget(['display_type' => 'contactbuttons']);
        return $result;
    }

    public static function renderChat($chat_type = '', $chat_url = '') {
	    if (empty($chat_type))
            $chat_type = FConfig::setting('chat_type', 'chat_facebook');
        if (empty($chat_url))
            $chat_url = FConfig::setting('chat_url', 'https://code.tidio.co/3zqbdsmxw4qy5tpiss8l6dbjjvfvughj.js');
        $result = '';

        $result = \common\widgets\fsocialshare\FChat::widget(['display_type' => 'chat_tidio']);
        $result .= \common\widgets\fsocialshare\FChat::widget(['display_type' => 'chat_livezilla']);

        return $result;
    }
}