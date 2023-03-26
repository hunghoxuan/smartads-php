<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/03/2017
 * Time: 11:31 SA
 */

namespace common\components;

use backend\models\ApplicationBase;

class FApplication extends ApplicationBase
{
    const LOOKUP = [];

    public static function getFrontendMenu($controller = '', $action = '', $module = '')
    {
        return [];
    }

    public static function getSettings()
    {
        return FHtml::getApplicationParams();
    }

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP)) {
            $data = self::LOOKUP[$column];
            $data = FHtml::getComboArray($data);

            return $data;
        }

        return [];
    }

	public static function isNotEmptyApplication() {
		return !empty(DEFAULT_APPLICATION_ID); // neu trong thi tra ve false
	}
}