<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\widgets\FGridView;

use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = isset($moduleName) ? $moduleName : '<?= StringHelper::basename($generator->modelClass) ?>';
$moduleTitle = isset($moduleTitle) ? $moduleTitle : '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = isset($moduleKey) ? $moduleKey : '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$object_type = isset($object_type) ? $object_type : '<?= str_replace('-', '_', Inflector::camel2id(StringHelper::basename($generator->modelClass))) ?>';

$this->title = FHtml::t($moduleTitle);

$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
$viewType = isset($viewType) ? $viewType : FHtml::getRequestParam('view');
$gridControl = $folder . '_columns.php';

?>
<div class="row">
    <div class="col-md-10 col-xs-12">
        <div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
            <?= "<?php " ?> if ($this->params['displayPortlet']): <?= "?>\n" ?>
            <div class="<?= "<?=" ?> $this->params['portletStyle'] <?= "?>" ?>">
                <div class="portlet-title hidden-print">
                    <div class="caption font-dark">
                <span class="caption-subject bold uppercase">
                <i class="<?= "<?php " ?> echo $this->params['mainIcon'] <?= "?>" ?>"></i>
                    <?= "<?= FHtml::t('common', \$moduleTitle)?>" ?></span>
                        <span class="caption-helper"><?= "<?= " ?> FHtml::t('common', 'title.index') <?= "?>" ?></span>
                    </div>
                    <div class="tools">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body">
                    <?= "<?php " ?> endif; <?= "?>" ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="ajaxCrudDatatable"
                                 class="<?= "<?php" ?> if (!$this->params['displayPortlet']) echo 'portlet light ' . ($viewType != 'print' ? 'bordered' : '');  <?= "?>" ?>">
                                <?= "<?=" ?>FGridView::widget([
                                'id'=>'crud-datatable',
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'object_type' => $object_type,
                                'edit_type' => FHtml::EDIT_TYPE_INLINE,
                                'render_type' => FHtml::RENDER_TYPE_AUTO,
                                'readonly' => !FHtml::isInRole('', 'update', $currentRole),
                                'field_name' => ['name', 'title'],
                                'field_description' => ['overview', 'description'],
                                'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                                'field_business' => ['', ''],
                                'toolbar' => $this->render('_toolbar.php'),
                                'columns' => require(__DIR__.'/'.$gridControl),
                                ])<?= "?>\n" ?>
                            </div>
                        </div>
                    </div>
                    <?= "<?php " ?> if ($this->params['displayPortlet']): <?= "?>" ?>
                </div>
            </div>
            <?= "<?php " ?> endif; <?= "?>" ?>
        </div>
    </div>
    <div class="col-md-2 col-xs-12">
        <?php echo "<?= FHtml::buildGridFiltersVertical(\$object_type, ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top']) ?>"; ?>
    </div>
</div>
<?= '<?php Modal::begin([
    "id"=>"ajaxCrubModal",
    "footer"=>"",
    ])?>' . "\n" ?>
<?= '<?php Modal::end(); ?>' ?>

