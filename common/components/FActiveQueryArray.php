<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use backend\modules\cms\models\CmsBlogs;
use backend\modules\wp\models\WpPosts;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


class FActiveQueryArray extends FActiveQuery
{
    public function all($db = null)
    {
        $model = $this->getModel();
        //return isset($model) ? $model::findAll($this->where, $this->orderBy, $this->limit, $this->offset) : [];
        $arr = $model::findArray($this->where, $this->orderBy, $this->limit, $this->offset);

        $result = [];
        $i = 1;

        if (!empty($arr) && is_array($arr))
        {
            foreach ($arr as $key => $arr_item) {
                $model = $model::createNew($arr_item);
                $result = array_merge($result, [$key => $model]);
            }
        }
        return $result;
    }

    public function one($db = null)
    {
        $model = $this->getModel();

        $models = $model::findArray();
        $condition = $this->where;
        $i = 1;


        foreach ($models as $key => $value) {

            if ((is_numeric($condition) && $i == $condition) || (is_string($condition) && $key == $condition) || (is_array($condition) && FHtml::array_existed($condition, $value))) {
                $model = $model::createNew($value);
                return $model;
            }
            $i += 1;

        }

        return null;
    }
}