<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\bootstrap\Modal;
use common\widgets\FGridView;
use common\components\CrudAsset;
use common\components\FHtml;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = '<?=StringHelper::basename($generator->modelClass)?>';
$moduleTitle = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$object_type = '<?= str_replace('-', '_', Inflector::camel2id(StringHelper::basename($generator->modelClass))) ?>';

$this->title = FHtml::t($moduleTitle);

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$role = isset($role) ? $role : FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
$viewType = isset($viewType) ? $viewType : FHtml::getRequestParam('view');
$gridControl = $folder . '_columns.php';

?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <?= "<?php " ?>if ($this->params['displayPortlet']): <?= "?>\n" ?>
    <div class="<?= "<?=" ?> $this->params['portletStyle'] <?= "?>" ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title font-blue-madison bold uppercase">
                <?php echo "<?= FHtml::buildAdminToolbar(\$object_type, ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top']) ?>"; ?>
            </div>
            <div class="tools">
                <a href="#" class="fullscreen"></a>
                <a href="#" class="collapse"></a>
            </div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body">
            <?= "<?php "?>endif; <?= "?>\n"?>
            <div class="row">
                <div class="col-md-12">
                    <div id="ajaxCrudDatatable" class="<?= "<?= " ?>!$this->params['displayPortlet'] ? 'portlet light ' . ($viewType != 'print' ? 'bordered' : '') : ''; <?= "?>" ?>">
                        <?= "<?= " ?>FGridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'toolbar' => $this->render('_toolbar.php'),
                            'columns' => require(__DIR__ . '/' . $gridControl),

                            //'object_type' => $object_type,
                            //'readonly' => !FHtml::isInRole('', 'update', $role),
                            //'field_name' => ['name', 'title'],
                            //'field_description' => ['overview', 'description'],
                            //'field_group' => ['category_id', 'type', 'status', 'is_hot', 'is_top', 'is_active'],
                            //'field_business' => ['', ''],
                            //'view' => 'grid'

                        ])<?= " ?>\n" ?>
                    </div>
                </div>
            </div>
            <?= "<?php " ?>if ($this->params['displayPortlet']): <?= "?>\n" ?>
        </div>
    </div>
<?= "<?php " ?>endif; <?= "?>\n" ?>
</div>
<?= '<?php Modal::begin([
    "id" => "ajaxCrubModal",
    "footer" => "",
]) ?>' . "\n" ?>
<?= '<?php Modal::end(); ?>' ?>