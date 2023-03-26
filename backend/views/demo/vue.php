<?php

use colee\vue\VueAsset;
use common\components\FHtml;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$baseUrl     = Yii::$app->getUrlManager()->getBaseUrl();

VueAsset::register($this);

?>
<!--Form-->
<div class="portlet light">

    <div id="app" class="vue">

        <p>{{ message }}</p>
        <button v-on:click="reverseMessage">Reverse Message</button>
    </div>

	<?php
	$script = <<<JS
        var app = new Vue({
            el:      "#app",
            data:    {
                message: "Hello Vue.js!"
            },
            methods: {
                reverseMessage: function () {
                    this.message = this.message.split("").reverse().join("");
                }
            }
        });
JS;

	$this->registerJs($script, \yii\web\View::POS_END);
	?>
</div>
<!--Markdown-->