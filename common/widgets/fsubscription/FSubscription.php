<?php
namespace common\widgets\fsubscription;
use common\widgets\BaseWidget;
use yii\base\Widget;

class FSubscription extends BaseWidget
{
    public $data;

    public function run()
    {
        if (empty($this->display_type))
            $this->display_type = 'subscription';

        return $this->render($this->display_type, array(
                'data' => $this->data,
                'allowAdminLogin' => false,
            )
        );
    }
}

?>