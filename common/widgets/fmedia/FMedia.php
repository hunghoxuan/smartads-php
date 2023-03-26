<?php
namespace common\widgets\fmedia;

use common\components\FFile;
use common\components\FHtml;
use common\widgets\BaseWidget;

class FMedia extends BaseWidget
{
    public function run()
    {
        $this::prepareData();

        return $this->RenderWidget($this->display_type, array(
                'items' => $this->items,
                'id' => $this->id
            )
        );
    }

    protected function prepareData() {
        if (empty($this->display_type))
            $this->display_type = 'jwplayer';
    }

    public static function makePlaylist($path, $extensions = ['mp4', 'avi', 'mkv'], $recursive = true, $match = null) {
        $contents = FFile::listContents($path, $recursive, $extensions);
        return $contents;
    }
}

?>