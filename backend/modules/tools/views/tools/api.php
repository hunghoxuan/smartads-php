<?php
use common\components\CrudAsset;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Api';
$moduleTitle = 'Api';
$moduleKey = 'api';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
if (!isset($url))
    $url = FHtml::getRequestParam('url');
$object = !isset($object) ? FHtml::getRequestParam('object') : $object;
$params = !isset($params) ? FHtml::getRequestParam('params') : $params;
$limit = !isset($limit) ? FHtml::getRequestParam('limit') : $limit;
$orderby = !isset($orderby) ? FHtml::getRequestParam('orderby') : $orderby;
$fields = !isset($fields) ? FHtml::getRequestParam('fields') : $fields;

?>

<div class="test-index">
    <form id="test" method="get" action="<?= FHtml::createUrl('/tools/api') ?>">
        <div class="row">
            <div class="col-md-2">
                <small>object</small>
                <input type="text" name="object" id="object" value="<?= $object ?>" class="form-control"/>
            </div>
            <div class="col-md-2">
                <small>params</small>
                <input type="text" name="params" id="params" value="<?= $params ?>" class="form-control"/>
            </div>
            <div class="col-md-2">
                <small>orderby</small>
                <input type="text" name="orderby" id="orderby" value="<?= $orderby ?>" class="form-control"/>
            </div>
            <div class="col-md-2">
                <small>limit</small>
                <input type="text" name="limit" id="limit" value="<?= $limit ?>" class="form-control"/>
            </div>
            <div class="col-md-4">
                <small>fields</small>
                <input type="text" name="fields" id="fields" value="<?= $fields ?>" class="form-control"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <br/>
                <b>URL</b>
                <input type="text" name="url" id="url" value="" class="form-control"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <br/>
                <button type="submit" name="action" value="view" class="btn btn-blue" onclick="updateAjax()">Run</button>
            </div>
        </div>
    </form>
    <br/><br/>
    <b><?= $url ?></b>

    <?= FHtml::var_dump($model) ?>
</div>

<script>
    function updateAjax() {
        url1 = $('#key').val();
        $.showLoading({allowHide: true});

        $.ajax({
            url: url1,
            type: 'post',
            success: function (data) {
                if (data !== '')
                    alert(data);
                refreshPage();
            }
        });
    }
</script>