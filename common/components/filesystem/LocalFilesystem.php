<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace common\components\filesystem;

use common\components\FHtml;
use creocoder\flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * LocalFilesystem
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class LocalFilesystem extends Filesystem
{
    /**
     * @var string
     */
    public $path = '@backend/..';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->path = '@backend/..';

        $this->path = Yii::getAlias($this->path);

        parent::init();
    }

    /**
     * @return Local
     */
    protected function prepareAdapter()
    {
        return new Local($this->path);
    }

    public function createDir($path, $config = []) {
        $path = str_replace('\\', '/', $path);
        $arr = explode('/', $path);

        $path = FHtml::getRootFolder();
        foreach ($arr as $sub_folder) {
            $path .= DIRECTORY_SEPARATOR . $sub_folder;
            if (!file_exists($path)) {
                mkdir($path);
                chmod($path, 0777);
            }
        }

        return true;
    }
}
