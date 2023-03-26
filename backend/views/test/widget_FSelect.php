<?php

use common\components\FHtml;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');

FHtml::var_dump($_POST);

$data = FHtml::getComboArray('cms_blogs.category_id');
$data = \backend\models\ObjectCategory::findAll();

?>

<form action = "<?= FHtml::createUrl('test', ['view' => 'widget_FSelect.php']) ?>" method="post">

    <div class="col-md-6">
        <?php FHtml::var_dump($data); ?>
    </div>

    <div class="col-md-6">
        <?= \common\widgets\fselect\FSelect::widget(['id' => 'select', 'name' => 'select', 'data' => $data, 'multiple' => true, 'value' => (isset($_POST['select']) ? $_POST['select'] : null)]) ?>

        <?= \common\widgets\fselect\FSelect::widget(['id' => 'select2', 'type' => 'ddslick', 'name' => 'select2', 'data' => $data, 'multiple' => true, 'value' => (isset($_POST['select2']) ? $_POST['select2'] : null)]) ?>
    </div>

    <button type="submit" value="Submit">Submit</button>
</form>
