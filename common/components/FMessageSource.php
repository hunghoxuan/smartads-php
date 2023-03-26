<?php
namespace common\components;

use backend\modules\system\models\SettingsText;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\i18n\PhpMessageSource;

class FMessageSource extends PhpMessageSource
{
    public function init()
    {
        parent::init();

        if (empty($this->basePath) || $this->basePath == '@app/messages')
            $this->basePath = '@common/messages';

        if (empty($this->fileMap))
            $this->fileMap = [
                'common' => 'common.php',
            ];
    }

    protected function loadMessagesFromFile($messageFile)
    {
        $application_id = FHtml::currentApplicationId();
        $messages = [];
        if (is_file($messageFile)) {
            $messages = include($messageFile);
            if (!is_array($messages)) {
                $messages = [];
            }
        }

        if (strpos($messageFile, 'common/') !== false) {
            $messageFile1 = str_replace('common/', "applications/$application_id/", $messageFile);

            if (is_file($messageFile1)) {
                $messages1 = include($messageFile1);
                if (is_array($messages1)) {
                    $messages = array_merge($messages, $messages1);
                }
            }
        }

        if (strpos($messageFile, 'backend/') !== false) {
            $messageFile1 = str_replace('backend/', "applications/$application_id/backend/", $messageFile);

            if (is_file($messageFile1)) {
                $messages1 = include($messageFile1);
                if (is_array($messages1)) {
                    $messages = array_merge($messages, $messages1);
                }
            }
        }

        return $messages;
    }
}