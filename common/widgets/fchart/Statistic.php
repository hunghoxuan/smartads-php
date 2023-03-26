<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/18/2018
 * Time: 10:47 AM
 */

namespace common\widgets\fchart;


use common\components\FHtml;
use yii\db\Exception;
use yii\helpers\StringHelper;

abstract class Statistic
{
	const RED_INTENSE  = 'red-intense';
	const GREEN_HAZE   = 'green-haze';
	const PURPLE_PLUM  = 'purple-plum';
	const BLUE_MADISON = 'blue-madison';
	const COLOR_ARR = [Statistic::RED_INTENSE, Statistic::GREEN_HAZE, Statistic::PURPLE_PLUM, Statistic::BLUE_MADISON];

	public static function showHtmlStatistic($total, $description, $icon = '', $link = '#', $color = self::BLUE_MADISON) {
		$view_detail = $link != '#' ? FHtml::t('common', 'View More') : '';
		$html        = <<<HTML
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <a href="$link" title="$view_detail">
                    <div class="dashboard-stat $color">
                        <div class="visual">
                            <i class="$icon"></i>
                        </div>
                        <div class="details">
                            <div class="number">$total</div>
                            <div class="desc">$description</div>
                        </div>
                        <a class="more" href="$link">
                        	$view_detail <i class="m-icon-swapright m-icon-white"></i>
                  		</a>
                    </div>
                </a>
            </div>
HTML;

		return $html;
	}

	/**
	 * @param bool $show_title
	 * @return string
	 * @throws Exception
	 */
	public static function show($show_title = false) {
		return '';
	}

    public static function render($show_title = false) {
        return static::show($show_title);
    }
}