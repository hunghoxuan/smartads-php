<?php
use common\components\FHtml;
use common\widgets\FBreadcrumbs;
use frontend\components\Helper;

?>
<div class="container">
    <?=
    FBreadcrumbs::widget([
        'homeLink' => [
            'label' => FHtml::t('common', '<i class="fa fa-home" aria-hidden="true"></i> Home'),
            'url' => '/home',
        ],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ])
    ?>
</div>