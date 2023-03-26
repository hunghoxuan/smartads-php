<?php

namespace common\components;

use yii\base\Component;
use yii\helpers\StringHelper;

require FHtml::getRootFolder() . '/node/node.php';

/**
 * Backup component
 *
 * @package demi\backup
 */
class NodeJS extends Component
{
    public static function start($cmd = '', $app = 'server.js') {
        if (!empty(NODE_DIR) && !is_dir(NODE_DIR))
            return;

        $dir = NODE_DIR;

        if (!empty($dir) && !StringHelper::endsWith($dir, '/'))
            $dir = $dir . '/';

        if (empty($cmd))
            $cmd = $dir . 'node ' . FHtml::getRootFolder() . '/node/' . $app;

        \common\components\FHtml::execSystemCommand($cmd);
    }
}