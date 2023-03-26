<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'User';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\models\User */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Users';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="user-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'code',
                'name',
                'username',
                'image',
                'overview',
                'content',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'birth_date',
                'birth_place',
                'gender',
                'identity_card',
                'email',
                'phone',
                'skype',
                'address',
                'country',
                'state',
                'city',
                'organization',
                'department',
                'position',
                'start_date',
                'end_date',
                'lat',
                'long',
                'rate',
                'rate_count',
                'card_number',
                'card_name',
                'card_exp',
                'card_cvv',
                'balance',
                'point',
                'role',
                'type',
                'status',
                'is_online',
                'last_login',
                'last_logout',
                'created_at',
                'updated_at',
                'application_id',
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
                'code',
                'name',
                'username',
                'image',
                'overview',
                'content',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'birth_date',
                'birth_place',
                'gender',
                'identity_card',
                'email',
                'phone',
                'skype',
                'address',
                'country',
                'state',
                'city',
                'organization',
                'department',
                'position',
                'start_date',
                'end_date',
                'lat',
                'long',
                'rate',
                'rate_count',
                'card_number',
                'card_name',
                'card_exp',
                'card_cvv',
                'balance',
                'point',
                'role',
                'type',
                'status',
                'is_online',
                'last_login',
                'last_logout',
                'created_at',
                'updated_at',
                'application_id',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

