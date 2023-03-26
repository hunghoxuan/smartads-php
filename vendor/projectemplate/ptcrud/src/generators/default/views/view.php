<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use projectemplate\ptcrud\Helper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$modelClass = StringHelper::basename($generator->modelClass);

$image_fields = array(
    'thumbnail',
    'image',
    'icon',
    'logo',
    'avatar',
    'cover'
);

$folder_name = Inflector::camel2id($modelClass);
$lower_name = str_replace('-','_',$folder_name);
$tableSchema = $generator->getTableSchema();

$lookup = array();
$dropdown = array();
$editor = array();

foreach ($tableSchema->columns as $column) :
    if (!empty($column->comment)) {
        $check_keyword = Helper::keyword($column->comment);
        if ($check_keyword !== false) {
            $keyword = $check_keyword;
            if ($keyword == Helper::DROPDOWN_KEYWORD) {
                $dropdown[] = $column->name;
            }
            if ($keyword == Helper::LOOKUP_KEYWORD){
                $lookup[$column->name] = Helper::lookupDataFromDbComment($column->comment, $keyword);
            }
        }
    }
endforeach;

foreach ($tableSchema->columns as $column) :
    if($column->type == "text"){
        $editor[] = $column->name;
    }
endforeach;

echo "<?php\n";
?>

use yii\widgets\DetailView;
use yii\helpers\Html;
use common\components\FHtml;
use common\widgets\FDetailView;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
?>
<?= "<?php" ?> if (!Yii::$app->request->isAjax) {
    $this->title = <?= $generator->generateString(Inflector::camel2words($modelClass)) ?>;
    $this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words($modelClass)) ?>, 'url' => 'index'];
    $this->params['breadcrumbs'][] = Yii::t('common', 'title.view');
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
}<?= " ?>" ?>
<?= '<?php if (Yii::$app->request->isAjax) { ?>' . "\n" ?>
    <div class="<?= Inflector::camel2id($modelClass) ?>-view">
        <?= "<?= " ?>FDetailView::widget([
            'model' => $model,
            'attributes' => [
<?php
    if (($tableSchema = $generator->getTableSchema()) === false) {
        foreach ($generator->getColumnNames() as $name) {
            echo "           '" . $name . "',\n";
        }
    } else {
        foreach ($generator->getTableSchema()->columns as $column) {
            $name = $column->name;
            $format = $generator->generateColumnFormat($column);
            if(in_array($name, $image_fields)) {
                echo "                " . "[" . "\n";
                echo "                    " . "'attribute' => '".$name."'," . "\n";
                echo "                    " . "'value' => FHtml::showImage(\$model->".$name.", 300, '". $folder_name ."')". ",\n";
                echo "                    " . "'format' => 'html'". ",\n";
                echo "                " . "]" . ",\n";
            }
            else{
                if($column->dbType == 'tinyint(1)')
                {
                    echo "                " . "[" . "\n";
                    echo "                    " . "'attribute' => '".$name."'," . "\n";
                    echo "                    " . "'value' => FHtml::showIsActiveLabel(\$model->".$name.")". ",\n";
                    echo "                    " . "'format' => 'html'". ",\n";
                    echo "                " . "]" . ",\n";
                }
                else{
                    //html editor
                    if(in_array($name, $editor)){
                        echo "                " . "[" . "\n";
                        echo "                    " . "'attribute' => '".$column->name."'," . "\n";
                        echo "                    " . "'format' => 'html'". ",\n";
                        echo "                " . "]" . ",\n";
                    }
                    //lookup
                    elseif(array_key_exists($name, $lookup)){
                        if(count($lookup[$name]) > 0){
                            echo "                " . "[" . "\n";
                            echo "                    " . "'attribute' => '".$column->name."'," . "\n";
                            echo "                    " . "'value' => ".ltrim($generator->modelClass,'\\')."::lookupLabel('".$lookup[$name]['table']."', '".$lookup[$name]['key']."', \$model->". $name .", '".$lookup[$name]['value']."')". ",\n";
                            echo "                " . "]" . ",\n";
                        }else{
                            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                        }
                    }
                    //dropdown
                    elseif (in_array($name, $dropdown)){
                        echo "                " . "[" . "\n";
                        echo "                    " . "'attribute' => '".$column->name."'," . "\n";
                        echo "                    " . "'value' => ".ltrim($generator->modelClass,'\\')."::". $name ."Label(\$model->". $name .")". ",\n";
                        echo "                    " . "'format' => 'html'". ",\n";
                        echo "                " . "]" . ",\n";
                    }else{
                        //if($name == "attachment") {echo 123;die;}
                        echo "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                    }
                }
            }
        }
    }
?>
            ],
        ]) ?>
    </div>
<?= '<?php } else { ?>' . "\n" ?>
    <div class="<?= "<?=" ?> $this->params['portletStyle'] <?= "?>" ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
                <span class="caption-subject bold uppercase">
                    <i class="<?= "<?php " ?>echo $this->params['mainIcon'] <?= "?>" ?>"></i><?= "<?= " ?><?= $generator->generateString(Inflector::camel2words($modelClass)) ?><?= " ?>\n" ?>
                </span>
                <span class="caption-helper"><?= "<?= " ?>Yii::t('common', 'title.view') <?= "?>\n" ?></span>
            </div>
            <div class="tools">
                <a href="#" class="collapse"></a>
                <a href="#" class="fullscreen"></a>
            </div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <?= "<?= " ?>FDetailView::widget([
                        'model' => $model,
                        'attributes' => [
<?php if (($tableSchema = $generator->getTableSchema()) === false) {
                            foreach ($generator->getColumnNames() as $name) {
                                echo "            '" . $name . "',\n";
                            }
                        } else {
                            foreach ($generator->getTableSchema()->columns as $column) {
                                $format = $generator->generateColumnFormat($column);
                                $name = $column->name;
                                if (in_array($name, $image_fields)) {
                                    echo "                            " . "[" . "\n";
                                    echo "                                " . "'attribute' => '" . $name . "'," . "\n";
                                    echo "                                " . "'value' => FHtml::showImage(\$model->" . $name . ", 500, '" . $folder_name . "')" . ",\n";
                                    echo "                                " . "'format' => 'html'" . ",\n";
                                    echo "                            " . "]" . ",\n";
                                } else {
                                    if ($column->dbType == 'tinyint(1)') {
                                        echo "                            " . "[" . "\n";
                                        echo "                                " . "'attribute' => '" . $name . "'," . "\n";
                                        echo "                                " . "'value' => FHtml::showIsActiveLabel(\$model->" . $name . ")" . ",\n";
                                        echo "                                " . "'format' => 'html'" . ",\n";
                                        echo "                            " . "]" . ",\n";
                                    } else {
                                        //html editor
                                        if(in_array($name, $editor)){
                                            echo "                            " . "[" . "\n";
                                            echo "                                " . "'attribute' => '".$column->name."'," . "\n";
                                            echo "                                " . "'format' => 'html'". ",\n";
                                            echo "                            " . "]" . ",\n";
                                        }
                                        //lookup
                                        elseif(array_key_exists($name, $lookup)){
                                            if(count($lookup[$name]) > 0){
                                                echo "                            " . "[" . "\n";
                                                echo "                                " . "'attribute' => '".$column->name."'," . "\n";
                                                echo "                                " . "'value' => ".ltrim($generator->modelClass,'\\')."::lookupLabel('".$lookup[$name]['table']."', '".$lookup[$name]['key']."', \$model->". $name .", '".$lookup[$name]['value']."')". ",\n";
                                                echo "                            " . "]" . ",\n";
                                            }else{
                                                echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                            }
                                        }
                                        //dropdown
                                        elseif (in_array($name, $dropdown)){
                                            echo "                            " . "[" . "\n";
                                            echo "                                " . "'attribute' => '".$column->name."'," . "\n";
                                            echo "                                " . "'value' => ".ltrim($generator->modelClass,'\\')."::". $name ."Label(\$model->". $name .")". ",\n";
                                            echo "                                " . "'format' => 'html'". ",\n";
                                            echo "                            " . "]" . ",\n";
                                        }else {
                                            echo "                            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                        }
                                    }
                                }
                            }
                        } ?>
                        ],
                    ]) ?>
                    <p>
                        <?= "<?= " ?>Html::a(Yii::t('common', 'Update'), ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
                        <?= "<?= " ?>Html::a(Yii::t('common', 'Delete'), ['delete', <?= $urlParams ?>], ['class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item ?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= "<?= " ?>Html::a(Yii::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?= '<?php } ?>' ?>