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
 * string $item['subtitle'];
 */

use miloschuman\highcharts\Highcharts;

//$item['series'] = [[
//        'name' => 'Tokyo',
//        'data' => [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
//    ], [
//    'name' => 'London',
//        'data' => [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//    ], [
//    'name' => 'London',
//        'data' => [5, 6.2, 3.7, 2.5, 31.9, 25.2, 7.0, 6.6, 4.2, 1.3, 5.6, 8.8]
//    ]];
//\common\components\FHtml::var_dump($item['series']);
if (isset($item['is_demo']) && $item['is_demo']) {
	if ($item['is_multiple']) {
		$item['series'] = [
			[
				'name' => 'Tokyo',
				'color' => '#eeaacc',
				'data' => [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
			],
			[
				'name' => 'London',
				'color' => '#ff00dd',
				'data' => [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
			],
			[
				'name' => 'London',
				'color' => '#ff0000',
				'data' => [5, 6.2, 3.7, 2.5, 15.9, 25.2, 7.0, 6.6, 4.2, 1.3, 5.6, 8.8]
			]
		];
	}
	else {
		$item['series'] = [
			[
				'name' => 'Tokyo',
				'color' => '#ff0000',
				'data' => [14.0, 11.9, 11.5, 14.5, 13.4, 14.5, 13.2, 11.5, 12.3, 11.3, 13.9, 11.6]
			]
		];
	}
	$item['title']    = "DEMO CHART";
	$item['category'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
}
//\common\components\FHtml::var_dump($item['series']);
?>

<?= Highcharts::widget([
	'scripts' => [
		'modules/drilldown',
		'modules/exporting',
		//        'themes/sand-signika',
	],
	'options' => [
		'chart'       => [
			'type'   => 'line',
			//'height' => $item['height']
		],
		'title'       => [
			'text' => $item['title']
		],
		'subtitle'    => [
			'text' => isset($item['subtitle']) ? $item['subtitle'] : ''
		],
		'plotOptions' => [
			'line' => [
				'dataLabels'          => [
					'enabled' => true
				],
				'enableMouseTracking' => false
			]
		],
		'series'      => $item['series'],
		'xAxis'       => [
			'categories' => is_array($item['category']) ? $item['category'] : [isset($item['category']) ? $item['category'] : '']
		],
		'yAxis'       => [
			'title' => ['text' => null],
		],
	]
]); ?>


