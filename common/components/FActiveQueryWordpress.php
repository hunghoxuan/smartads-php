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


class FActiveQueryWordpress extends FActiveQuery
{

}