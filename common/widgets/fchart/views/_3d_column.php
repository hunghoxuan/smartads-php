<?php

/* @var $this yii\web\View */
/* @var $item array */
/* @var $options array */

/**
 * items in array item
 * -- * is required --
 * string $item['title'] *;
 * array $item['series'] *;
 * string|array $item['category'];
 * bool $item['is_3d'];
 */

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

$scripts = [
	'modules/drilldown',
	'modules/exporting',
	//        'themes/sand-signika',
];
if (isset($item['is_3d'])) {
	$scripts += ['highcharts-3d'];
}

?>

<?= Highcharts::widget([
	'scripts' => $scripts,
	'options' => [
		'chart'       => [
			'type'      => 'column',
			'options3d' => [
				'enabled' => true,
				'alpha'   => 10,
				'beta'    => 25,
				'depth'   => 100
			],
			//'height'    => $item['height']
		],
		'title'       => [
			'text' => $item['title']
		],
		'plotOptions' => [
			'column' => [
				'depth' => 25
			],
			'pie'    => [
				'depth' => 234
			]
		],
		'series'      => $item['series'],
		'xAxis'       => [
			'categories' => isset($item['category']) ? (is_array($item['category']) ? $item['category'] : [$item['category']]) : [],
			'labels'     => [
				'skew3d' => true,
				'style'  => [
					'fontSize' => '16px'
				]
			]
		],
		'yAxis'       => [
			'title' => ['text' => null],
		],
	]
]); ?>


