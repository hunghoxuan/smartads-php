<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-30
 * Time: 22:36
 */

namespace common\components;

use Yii;
use yii\web\Link;
use yii\web\Request;

class FPagination extends \yii\data\Pagination
{
	public function createUrl($page, $pageSize = null, $absolute = false) {
		if (is_numeric($page)) {
			$page = $page + 1;
		}

		return FHtml::currentUrl(['page' => $page]);

		//        $page = (int) $page;
		//        $pageSize = (int) $pageSize;
		//        if (($params = $this->params) === null) {
		//            $request = Yii::$app->getRequest();
		//            $params = $request instanceof Request ? $request->getQueryParams() : [];
		//        }
		//
		//        if ($page > 0 || $page >= 0 && $this->forcePageParam) {
		//            $params[$this->pageParam] = $page + 1;
		//        } else {
		//            unset($params[$this->pageParam]);
		//        }
		//        if ($pageSize <= 0) {
		//            $pageSize = $this->getPageSize();
		//        }
		//        if ($pageSize != $this->defaultPageSize) {
		//            $params[$this->pageSizeParam] = $pageSize;
		//        } else {
		//            unset($params[$this->pageSizeParam]);
		//        }
		//        $params[0] = $this->route === null ? Yii::$app->controller->getRoute() : $this->route;
		//        $urlManager = new FUrlManager();
		//        $urlManager = $urlManager === null ? Yii::$app->getUrlManager() : $urlManager;
		//        if ($absolute) {
		//            return $urlManager->createAbsoluteUrl($params);
		//        } else {
		//            return $urlManager->createUrl($params);
		//        }
	}


	/**
	 * @param bool $absolute
	 * @return array
	 */
	public function getLinksWithNumber($absolute = false) {
		$currentPage = $this->getPage();
		$pageCount   = $this->getPageCount();

		$links = [];
		if ($currentPage > 0) {
			$links[self::LINK_FIRST] = $this->createUrl(0, null, $absolute);
			$links[self::LINK_PREV]  = $this->createUrl($currentPage - 1, null, $absolute);
		}

		for ($index = 0; $index <= $pageCount - 1; $index++) {
			$links[$index  + 1] = $this->createUrl($index, null, $absolute);
		}

		if ($currentPage < $pageCount - 1) {
			$links[self::LINK_NEXT] = $this->createUrl($currentPage + 1, null, $absolute);
			$links[self::LINK_LAST] = $this->createUrl($pageCount - 1, null, $absolute);
		}

		return $links;
	}
}