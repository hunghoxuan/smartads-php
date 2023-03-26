<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;
use yii\i18n\I18N;
use yii\i18n\MessageSource;


class FI18N extends I18N
{
    /**
     * Initializes the component by configuring the default message categories.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->translations['yii']) && !isset($this->translations['yii*'])) {
            $this->translations['yii'] = [
                'class' => 'common\components\FPhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@yii/messages',
            ];
        }
        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => 'common\components\FPhpMessageSource',
                'sourceLanguage' => \Yii::$app->sourceLanguage,
                'basePath' => '@app/messages',
            ];
        }
    }

    /**
     * Returns the message source for the given category.
     * @param string $category the category name.
     * @return MessageSource the message source for the given category.
     * @throws InvalidConfigException if there is no message source available for the specified category.
     */
    public function getMessageSource($category)
    {
        if (!key_exists($category, $this->translations))
            $category = 'common*';

        return parent::getMessageSource($category);

    }
}