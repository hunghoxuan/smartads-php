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
 * bool $item['is_stack'];
 */

use miloschuman\highcharts\Highcharts;

$scripts = [
	'modules/drilldown',
	'modules/exporting',
	//        'themes/sand-signika',
];

if (isset($item['is_demo']) && $item['is_demo']) {
	$item['series']   = [
		[
			'type'   => 'column',
			'name'   => 'Jane',
			'data'   => [3, 2, 1, 3, 4],
			'stack ' => 'test'
		],
		[
			'type'   => 'column',
			'name'   => 'Jane',
			'data'   => [3, 2, 1, 3, 4],
			'stack ' => 'test'
		],
		[
			'type' => 'spline',
			'name' => 'Average',
			'data' => [3, 2.67, 3, 6.33, 3.33]
		],
		[
			'type' => 'spline',
			'name' => 'Average1',
			'data' => [4, 2, 2, 10, 11]
		]
	];
	$item['category'] = ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums'];
	$item['title']    = 'DEMO CHART';
}

?>
<?php
$yAxis = [];
if (isset($item['is_multiple_yAxis'])) {
	$yAxis = array_merge($item['yAxis'], [
		[
			'title'  => [
				'text'  => '',
				'style' => [
					'color' => "Highcharts.getOptions().colors[0]"
				]
			],
			'labels' => [
				'format' => '{value}',
				'style'  => [
					'color' => "Highcharts.getOptions().colors[0]"
				]
			]
		],
		[
			'labels'   => [
				'format' => '{value} %',
				'style'  => [
					'color' => "Highcharts.getOptions().colors[1]"
				]
			],
			'title'    => [
				'text'  => '',
				'style' => [
					'color' => "Highcharts.getOptions().colors[1]"
				]
			],
			'opposite' => true
		]
	]);
}
else {
	$yAxis = empty($item['yAxis']) ? ['title' => ['text' => null]] : $yAxis;
}
?>
<?= Highcharts::widget([
	'scripts' => $scripts,
	'options' => [
		'chart'       => [
			'height'   => $item['height'],
			'zoomType' => 'xy'
		],
		'title'       => [
			'text' => $item['title']
		],
		'plotOptions' => [
			'column' => ['stacking' => $item['is_stack'] ? 'normal' : '']
		],
		'series'      => array_values($item['series']),
		'xAxis'       => [
			'categories' => isset($item['category']) ? (is_array($item['category']) ? $item['category'] : [$item['category']]) : [],
		],
		'yAxis'       => $yAxis,
		'labels'      => [
			'items' => [
				[
					'html'  => '',
					'style' => [
						'left'  => '50px',
						'top'   => '18px',
						'color' => "(Highcharts . theme && Highcharts . theme . textColor) || 'black'"
					]
				]
			]
		],
	],
]); ?>


