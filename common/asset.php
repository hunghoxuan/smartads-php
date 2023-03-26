<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 10/29/2018
 * Time: 14:10
 */

if (!function_exists('the_assets_frontend')) {
	/**
	 * @param string $path
	 * @return string
	 */
	function the_assets_frontend($path = '') {
		echo assets_frontend($path);
	}
}

if (!function_exists('assets_frontend')) {
	/**
	 * @param string $path
	 * @param string $theme
	 * @return string
	 */
	function assets_frontend($path = '', $theme = '') {
		$baseUrl = \common\components\FHtml::currentFrontendBaseUrl($theme);
		$baseUrl .= "/assets/";

		return $baseUrl . $path;
	}
}

if (!function_exists('assets')) {
	/**
	 * @param string $path
	 * @param array  $params
	 * @return mixed|string
	 */
	function assets($path = '', $params = []) {
		//return Yii::$app->urlManager->createUrl($path, $params);
		return \yii\helpers\Url::to($path, $params);
	}
}
