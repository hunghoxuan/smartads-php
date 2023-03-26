<?php
/* @var $this yii\web\View */
/* @var $item array */
/* @var $options array */

/**
 * items in array item
 * -- * is required --
 * string $item['scripts'] *;
 * string $item['title'] *;
 * array $item['series'] *;
 * bool $item['enableLabel'] *;
 * int $item['size'];
 * bool $item['is_percentage'];
 */

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

if (isset($item['is_demo']) && $item['is_demo']) {
	$item['series'] = [
		[
			'name' => 'Share',
			'data' => [
				['name' => 'Chrome', 'y' => 61.41],
				['name' => 'Internet Explorer', 'y' => 11.84],
				['name' => 'Firefox', 'y' => 10.85],
				['name' => 'Edge', 'y' => 4.67],
				['name' => 'Safari', 'y' => 4.18],
				['name' => 'Other', 'y' => 7.05]
			]
		]
	];
}
?>
<?php
$is_percentage = isset($item['is_percentage']) ? $item['is_percentage'] : true;
$format        = '{point.percentage:.1f} %';
if (!$is_percentage) {
	$format = '{point.y}';
}

$has_colors = array_column($options, 'color');
$color_js   = array();
if (count($has_colors) != 0) {
	$colors = array();

	foreach ($options as $option) {
		$value = $option['value'];
		if (isset($option['color']) && count($option['color']) != 0) {
			$colors[$value] = $option['color'];
		}
		else {
			$colors[$value] = "#434348";
		}
	}

	$colors_objects = json_encode($colors);
	$script         = <<<JS
        var colors = $colors_objects;
        var colors_values = Object.values(colors);
        var customColor = Highcharts.map(colors_values, function (color) {
            return {
                radialGradient: {
                    cx: 0.5,
                    cy: 0.3,
                    r: 0.7
                },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        });
    
        getColor = [];        
        var i = 0;   
        Object.keys(colors).forEach(function eachKey(key) { 
            getColor[key] = customColor[i];
            i++;
        });
JS;

	$this->registerJs($script, \yii\web\View::POS_END);

}
else {
	$script   = <<<JS
    Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    })
JS;
	$color_js = new JsExpression($script);
}
?>


<?= Highcharts::widget([
	'scripts' => $item['scripts'],
	'options' => [
		'chart'       => [
			'type'                => 'pie',
			'plotBackgroundColor' => null,
			'plotBorderWidth'     => null,
			'plotShadow'          => false,
			//'height' => $item['height']
		],
		'title'       => [
			'text' => $item['title']
		],
		'tooltip'     => [
			'pointFormat' => "{series.name}: <b>$format</b>"
		],
		'plotOptions' => [
			'pie' => [
				'allowPointSelect' => true,
				'cusor'            => 'pointer',
				'dataLabels'       => [
					'enabled'        => $item['enableLabel'],
					'format'         => "<b>{point.name}</b>: $format",
					'style'          => [
						'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.contrastTextColor)') || 'black'
					],
					'connectorColor' => 'silver',
				],
				'showInLegend'     => true,
				'size'             => isset($item['size']) ? $item['size'] : 200
			]
		],
		'colors'      => $color_js,
		'series'      => $item['series']
	]
]);


/*
$this->registerJs("
console.log(Highcharts.getOptions().colors);
Highcharts.setOptions({
    colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    })
});
");
*/ ?>


