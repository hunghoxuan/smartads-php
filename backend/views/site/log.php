<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use common\components\FHtml;

?>

<div class="form">
    <div class="row">

        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        LOG
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                        </li>

                    </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= FHtml::currentLog() ?>
                                        <a class="btn btn-md btn-success" href="<?= FHtml::createUrl('site/log', ['action' => 'clear']) ?>">Clear</a>

                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="site-error">
</div>
