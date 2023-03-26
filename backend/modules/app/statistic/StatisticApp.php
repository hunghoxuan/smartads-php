<?php
namespace backend\modules\app\statistic;

use backend\modules\app\models\AppUser;
use backend\modules\app\models\AppUserTransaction;
use common\components\FHtml;
use common\widgets\fchart\Statistic;

/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 4/19/2018
 * Time: 4:05 PM
 */

class StatisticApp extends Statistic
{
    /**
     * @return string
     */
    public static function showUserOnline()
    {
        $users = AppUser::find()->where(['is_online' => 1])->count();
        return Statistic::showHtmlStatistic(number_format($users), FHtml::t('common', 'People Online'), 'fa fa-users');
    }

    /**
     * @return string
     */
    public static function showUsers()
    {
        $users = AppUser::find()->count();
        return Statistic::showHtmlStatistic(number_format($users), FHtml::t('common', 'Users'), 'fa fa-users', '#',Statistic::RED_INTENSE);
    }

    /**
     * @return string
     */
    public static function showUsersAndOnline()
    {
        $users = AppUser::find()->count();
        $usersOnline = AppUser::find()->where(['is_online' => 1])->count();
        return Statistic::showHtmlStatistic(number_format($users), FHtml::t('common', 'Users') .  ' - ' . $usersOnline . ' ' . FHtml::t('common', 'Online'), 'fa fa-users', '#',Statistic::RED_INTENSE);
    }

    /**
     * @return string
     */
    public static function showTotalTransaction()
    {
        $users = AppUserTransaction::find()->count();
        return Statistic::showHtmlStatistic(number_format($users), FHtml::t('common', 'Transactions'), 'fa fa-users', '#',Statistic::GREEN_HAZE);
    }
}