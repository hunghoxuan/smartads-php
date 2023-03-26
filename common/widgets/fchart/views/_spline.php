<?php

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $item array */
/**
 * items in array item
 * -- * is required --
 * string $item['scripts'] *;
 * string $item['title'] *;
 * string $item['subtitle'] *;
 * array $item['series'] *;
 * array $item['xAxis'] *;
 * array $item['yAxis'] *;
 */
?>

    <!--SPLINE-->
<?= Highcharts::widget([
	'scripts' => $item['scripts'],
	'options' => [
		'chart'       => [
			'type'   => 'spline',
			//'height' => $item['height']
		],
		'title'       => [
			'text' => $item['title']
		],
		'subtitle'    => [
			'text' => $item['subtitle']
		],
		'xAxis'       => $item['xAxis'],
		'yAxis'       => $item['yAxis'],
		'tooltip'     => [
			'crosshairs' => true,
			'shared'     => true
		],
		'plotOptions' => [
			'spline' => [
				'marker' => [
					'radius'    => 4,
					'lineColor' => '#666666',
					'lineWidth' => 1
				]
			]
		],
		'series'      => $item['series']
	]
]); ?>