<?php

use common\components\FHtml;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');

$types = \backend\modules\cms\models\CmsBlogs::getLookupCategoryArray();
$types = \backend\modules\cms\models\CmsBlogs::getLookupArray('category_id'); // or 'type', 'status' ...
$types = \common\components\FModel::getLookupArray('cms_blogs.category_id'); // or 'cms_blogs.type', 'cms_blogs.status' ...
FHtml::var_dump($types);

//$arr = FHtml::findArrayFromCSV(FHtml::getRootFolder() . "/backend/views/test/data.csv");
//FHtml::var_dump($arr);

$models = \backend\models\ObjectCategoryCSV::findOne('C1');
$models->first_name = $models->first_name . rand(0, 100);
$models->content = 'HHHH';
$models->save();
$models = \backend\models\ObjectCategoryCSV::findOne('C1');

FHtml::var_dump($models);
?>

