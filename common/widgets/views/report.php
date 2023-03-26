<?php

use common\components\FHtml;
use common\widgets\FActiveForm;

/**
 * @var array $dataList
 * @var array $field_group_by
 * @var array $field_date
 * @var array $group_date
 * @var array $group_type
 * @var array $dates
 */
//FHtml::var_dump($dataList);
$currentUrl = FHtml::currentUrl();
?>
<?php
$form = FActiveForm::begin([
	'id'                     => "$object_type-form",
	'type'                   => \kartik\form\ActiveForm::TYPE_VERTICAL, //ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
	'readonly'               => !true, // check the Role here
	'enableClientValidation' => true,
	'enableAjaxValidation'   => false,
	'options'                => [
		'enctype' => 'multipart/form-data',
        'style' => 'margin-bottom:10px !important'
	]
]);

?>
    <div class="hidden-print">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">
                    <div class="form-group">
                        <?= $form->field($model, 'type')->dropDownList($group_type_array)->label('Reports') ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <?= $form->field($model, 'group_by')->dropDownList(array_combine($field_group_by, FHtml::getFieldLabel($model, $field_group_by))) ?>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <?= $form->field($model, 'group_date')->dropDownList(array_combine($field_date, FHtml::getFieldLabel($model, $field_date)))->label('Date') ?>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <?= $form->field($model, 'date_type')->dropDownList(array_combine($group_date, $group_date))->label('Period') ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <?= $form->field($model, 'time_range')->dropDownList($time_range)->label('Time') ?>
                    </div>
                </div>
                <div class="col-md-2 time_range hide">
                    <div class="form-group">
                        <?= $form->field($model, 'start_date')->date() ?>
                    </div>
                </div>
                <div class="col-md-2 time_range hide">
                    <div class="form-group">
                        <?= $form->field($model, 'end_date')->date() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group" style="border-bottom: 1px solid lightgrey; padding-bottom: 10px">
                <?= FHtml::a('Filter', FHtml::createUrl($currentUrl), ['class' => ['btn btn-primary'], 'id' => 'filter']); ?>
                <?= FHtml::a('export', FHtml::createUrl($currentUrl . "&report_type=export"), ['class' => ['btn btn-default'], 'id' => 'report-export']); ?>
            </div>
        </div>
    </div>
<?php FActiveForm::end() ?>
<div class="row">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
			<?php
			$tabs = array_keys($dataList);
			foreach ($tabs as $key_t => $tab): ?>
                <li role="presentation" class="<?= $key_t == 0 ? 'active' : ''; ?>">
                    <a href="#tab<?= $key_t ?>" aria-controls="<?= $key_t; ?>" role="tab" data-toggle="tab"><?= key_exists($tab, $group_type_array) ? $group_type_array[$tab] : $tab; ?></a>
                </li>
			<?php endforeach; ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
			<?php $i = 0; foreach ($dataList as $k_d => $list): ?>
                <div role="tabpanel" class="tab-pane <?= $i == 0 ? 'active' : '';  ?>" id="tab<?= $i; $i++; ?>">
                 <?php if (!empty($list)) { ?>

                    <div class="col-md-12">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="col-md-2 col-xs-2"><?= FHtml::getFieldLabel($model, $model->group_by) ?> / <?= $model->date_type ?> </th>
								<?php foreach ($dates as $k => $date): ?>
                                    <th><?= $date ?></th>
								<?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach ($list as $key => $item): ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
									<?php foreach ($dates as $k => $date): ?>
										<?php if (isset($item['total'][$date])): ?>
                                            <td><?= $item['total'][$date]; ?></td>
										<?php else: ?>
                                            <td><?= 0 ?></td>
										<?php endif; ?>
									<?php endforeach; ?>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else {
                     echo FHtml::showEmptyMessage(false);
                 }?>
                    <?php if (!empty($dataChart[$k_d])) { ?>
                    <div class="col-md-12">
                        <h3><?= FHtml::t('common', 'Chart') ?> </h3>

                        <?php
						$chart           = new \common\widgets\fchart\models\ChartLineBasic;
						$chart->category = $dates;
						$chart->items    = $dataChart[$k_d];
						$chart->getChart();
						$chart->total_item = 12;
						//$chart->is_demo = 1;
						//$chart->is_multiple = 1;
						echo \common\widgets\fchart\Chart::widget(['data' => $chart, 'container' => false, 'title' => $chart->title, 'columns' => 12]);
						?>

                    </div>
                    <?php } ?>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
</div>

<?php
/** @var \yii\web\View $view */
$view   = FHtml::currentView();
$script = <<<JS
    $(function() {
        $('[id*="ecommerceorder"]').on('change', function() {
            prepareUrl(this);
        });
        
        $('#ecommerceorder-time_range').on('change', function() {
            if ($(this).val() === "range") {
                $('.time_range').removeClass("hide");
            } 
            else {
                $('.time_range').addClass("hide");
            }
            prepareUrl(this);
        });
        $('#ecommerceorder-start_date').on('change, blur', function() {
            let _val = $(this).val();
            setUrl('start_date', _val);
        });
        $('#ecommerceorder-end_date').on('change, blur', function() {
            let _val = $(this).val();
            setUrl('end_date', _val);
        });
        $(window).load(function() {
            let time_range = '{$model->time_range}';
            if (time_range === 'range') {
                $('.time_range').removeClass("hide");
            }
        });
    });

    function prepareUrl(_this) {
        let _name = $(_this).attr('name');
        if (typeof _name === "undefined") {
            return false;
        }
        let _type = _name.substr(_name.indexOf("[") + 1).replace("]", '');
        let _val = $(_this).val();
        setUrl(_type, _val);
    }

    function setUrl(_type, _val) {
        let url = $('#filter').attr('href');
        let _paramsArray = url.split("?");
        let _params = '';
        if (_paramsArray.length === 2) {
            _params = _paramsArray[1]; 
            _params = convertUriToObject(_params);
            _params[_type] = _val;
        }
        else {
            _params = "";
        }
        
        console.log(_val);
        if (_type === 'time_range') {
            if (_val === 'range') {
                _params['start_date'] = $('#ecommerceorder-start_date').val();
                _params['end_date'] = $('#ecommerceorder-end_date').val();
            } else {
                delete _params.start_date;
                delete _params.end_date;
            }
        } 
        
        _params = jQuery.param(_params);
        // console.log(_paramsArray.indexOf("?"));
        // console.log(_paramsArray[0]);
        if (_paramsArray.indexOf("?") > -1) {
            url = _paramsArray[0] + "&" + _params;
        } 
        else {
            url = _paramsArray[0] +"?" + _params;
        }
        $('#filter').attr('href', url);
    }
    /**
    * method parse uri to object
    * @param uri
    * @return object
    */
    function convertUriToObject(uri) {
        return JSON.parse('{"' + decodeURI(uri.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}'); 
    }
JS;
$view->registerJs($script, \yii\web\View::POS_END);
?>