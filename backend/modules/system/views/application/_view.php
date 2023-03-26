<?php

use common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'Application';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\Application */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Applications';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="application-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'logo',
                'code',
                'name',
                'description',
                'keywords',
                'note',
                'lang',
                'modules',
                'storage_max',
                'storage_current',
                'address',
                'map',
                'website',
                'email',
                'phone',
                'fax',
                'chat',
                'facebook',
                'twitter',
                'google',
                'youtube',
                'copyright',
                'terms_of_service',
                'profile',
                'privacy_policy',
                'is_active',
                'type',
                'status',
                'page_size',
                'main_color',
                'cache_enabled',
                'currency_format',
                'date_format',
                'web_theme',
                'admin_form_alignment',
                'body_css',
                'body_style',
                'page_css',
                'page_style',
                'owner_id',
                'created_date',
                'created_user',
                'modified_date',
                'modified_user',
    ],
    ]) ?>
</div>
<?php } else { ?>

        <div class="row" style="padding: 20px">
            <div class="col-md-12" style="background-color: white; padding: 20px">
                <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                                'id',
                'logo',
                'code',
                'name',
                'description',
                'keywords',
                'note',
                'lang',
                'modules',
                'storage_max',
                'storage_current',
                'address',
                'map',
                'website',
                'email',
                'phone',
                'fax',
                'chat',
                'facebook',
                'twitter',
                'google',
                'youtube',
                'copyright',
                'terms_of_service',
                'profile',
                'privacy_policy',
                'is_active',
                'type',
                'status',
                'page_size',
                'main_color',
                'cache_enabled',
                'currency_format',
                'date_format',
                'web_theme',
                'admin_form_alignment',
                'body_css',
                'body_style',
                'page_css',
                'page_style',
                'owner_id',
                'created_date',
                'created_user',
                'modified_date',
                'modified_user',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

