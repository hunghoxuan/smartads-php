<?php
namespace common\widgets\flogin;

use yii\base\Widget;

class FUser extends Widget
{
    public $data;

    public function run()
    {
        return $this->render('user', array(
                'data' => $this->data,
            )
        );
    }
}

?>