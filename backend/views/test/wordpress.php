<?php

use common\components\FHtml;
use backend\modules\wp\models\WpPosts;

/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$baseUrl     = Yii::$app->getUrlManager()->getBaseUrl();

$model = new WpPosts();
$models = \backend\modules\wp\models\WpPosts::find('wordpress')->where(['post_type' => 'post'])->all();

$host = "14.177.232.29:8000";
$url1 = "http://$host/api/wb/exam_pending?dept=C1.1&room=4";
$url2 = "http://$host/api/wb/roomlist?dept=C1";
$url3 = "http://$host/api/wb/roomlist?dept=C1";
$url4 = "https://audiovyvy.com/wp-api/api.php/list_audio?pages=1&end=10";
$url5 = "https://vnexpress.net";

echo $url1;
$data = \common\components\FApi::getUrlContent($url4);
$data = \backend\modules\cms\models\CmsBlogs::loadMultiple($data, ['name' => 'post_title', 'id' => 'ID']);

FHtml::var_dump($data); die;

die;

//var_dump($models);
?>
<!--Form-->
<div class="portlet light">
    <?
        FHtml::var_dump($models, true, $this);
    ?>

</div>
<div class="form col-md-12">

</div>
<hr/>
<div class="form col-md-12">

</div>
