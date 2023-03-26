<?php
namespace common\widgets\flogin;

use yii\base\Widget;

class FLogin extends Widget
{
    public $data;

    public function run()
    {
        return $this->render('login', array(
                'data' => $this->data,
                'allowAdminLogin' => false,
            )
        );
    }
}

?>