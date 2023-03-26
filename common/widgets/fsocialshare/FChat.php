<?php
namespace common\widgets\fsocialshare;

use common\components\FHtml;
use common\widgets\BaseWidget;

class FChat extends BaseWidget
{
    public $chat_url;
    public $background_css;
    public $color;
    public $left;

    protected function prepareData()
    {
        $this->page_code = !empty($this->page_code) ? $this->page_code : 'common';
        $this->show_headline = false;
        $this->display_type = !empty($this->display_type) ? $this->display_type : FHtml::settingWebsite('chat.type', 'chat_livezilla');
        parent::prepareData(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        self::prepareData();

        return "<div class='hidden-print'>" . $this->RenderWidget($this->display_type,
            [
                'chat_url' => $this->chat_url,
                'background_css' => $this->background_css,
                'color' => $this->color,
                'left' => $this->left
               ]) . "</div>";
    }
}

?>