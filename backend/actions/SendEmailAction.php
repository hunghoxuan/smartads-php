<?php
namespace backend\actions;

use common\components\FEmail;
use Yii;
use common\components\FHtml;

/**
 * @OA\Get(
 *     path="/api/send-email", summary="Send Email",
 *     @OA\Parameter(name="email_from", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Parameter(name="name_from", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Parameter(name="email", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Parameter(name="name", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Parameter(name="title", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Parameter(name="content", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response="200", description="Success")
 * )
 */
class SendEmailAction extends BaseAction
{
    public function run()
    {
        $content = FHtml::getRequestParam(['content', 'body']);
        $emailCC = FHtml::getRequestParam(['email_from', 'from', 'from_email', 'From']);
        $nameCC = FHtml::getRequestParam(['name_from', 'from_name']);
        $title = FHtml::getRequestParam(['title', 'subject']);
        $name = FHtml::getRequestParam(['name', 'Name']);
        $email = FHtml::getRequestParam(['email' , 'to']);
        $view = FHtml::getRequestParam('view');
        $params = FHtml::getRequestParam('params');

        FEmail::sendEmailDirect($email, $name, $title, $content, $emailCC, $nameCC, $view, $params);
    }
}
