<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace common\components;

use common\components\filesystem\FtpFilesystem;
use common\components\filesystem\LocalFilesystem;
use common\components\filesystem\SftpFilesystem;
use Yii;
use yii\base\Component;
use yii\swiftmailer\Mailer;


class FEmail extends Mailer
{
    const MAIL_CONTACT = 'Contact';
    const MAIL_ORDER = 'Order';
    const MAIL_TITLES = [
        'Contact' => 'Contact Confirmation',
        'Order' => 'Purchase Order Confirmation'
    ];

    public static function notifySystem($title, $content, $admin_model = null) {
        $adminEmail = FHtml::getAuthorEmail();
        $adminName = FHtml::getAuthor();
        $adminMail1 = FHtml::settingCompanyEmail(false);
        $adminName1 = FHtml::currentCompanyName();

        $content = '<hr/> <h3>Message: </h3>' . $content;

        if (isset($admin_model) && is_object($admin_model))
            $content .= '<hr/>' . FHtml::encode($admin_model);
        else if (is_array($admin_model)) {
            $content .= '<hr/> <h3>Information: </h3>';
            $content .= FHtml::showArrayAsTable($admin_model);
        }

        $content .= '<hr/> <h3>System Info: </h3>';
        $content .= FHtml::showArrayAsTable([
            'IP Address' => FHtml::currentIPAddress(),
            'Domain Name' => FHtml::currentDomain()
        ]);

        self::sendEmail($adminMail1, $adminName1, $adminEmail, $adminName, $title, $content);
    }

    public static function getAdminEmail() {
        $adminMail = FHtml::settingCompanyEmail(false);
        if (empty($adminMail))
            $adminMail = FHtml::getAuthorEmail();

        if (empty($adminMail))
            $adminMail = FConfig::getConfigValue("components/mailer/transport/username");
        return $adminMail;
    }

    public static function getAdminName() {
        $adminName = FHtml::currentCompanyName();

        if (empty($adminName))
            $adminName = FConfig::getAuthor();
        return $adminName;
    }

    public static function sendEmailDirect($email, $name, $title, $content, $email_from = '', $name_from = '', $view = '', $params = null) {
        if (!empty($view)) {
            $mail = Yii::$app->mailer->compose($view, $params);
        } else {
            $mail = Yii::$app->mailer->compose()
                ->setHtmlBody($content);
        }

        if (empty($email_from)) {
            $email_from = self::getAdminEmail();
        }

        if (empty($name_from)) {
            $name_from = self::getAdminName();
        }

        $admin_email = self::getAdminEmail();

        $mail->setTo([$email => $name])->setCc($admin_email)
            ->setFrom([$email_from => $name_from])
            ->setSubject($title);

        $mailer = Yii::$app->mailer;

        return $mailer->sendDirect($mail);
    }

    public static function sendEmail($email, $name, $title, $content, $email_from = '', $name_from = '', $view = '', $params = null) {
        if (empty($email_from)) {
            $email_from = self::getAdminEmail();
        }

        if (empty($name_from)) {
            $name_from = self::getAdminName();
        }

        $email_arr = FHtml::decode($email);
        $urls = [];

        if (is_array($email_arr)) {
            foreach ($email_arr as $email => $name1) {
                if (empty($name1))
                    $name1 = $name;
                $urls[] = FHtml::currentRootUrl() . FHtml::createUrl("api/sendEmail", ['email' => $email, 'name' => $name1, 'title' => $title, 'content' => $content, 'view' => $view, 'params' => $params, 'name_from' => $name_from, 'email_from' => $email_from]);
            }

            FSystem::execRemoteUrlsAsync($urls, [], false);

        } else {
            FSystem::execRemoteUrl('api/sendEmail', ['email' => $email, 'name' => $name, 'title' => $title, 'content' => $content, 'view' => $view, 'params' => $params, 'name_from' => $name_from, 'email_from' => $email_from], false);
        }
    }

    public static function sendEmailFromAdmin($client_email, $client_name, $type, $content, $view = '', $params = null)
    {
        $adminMail = self::getAdminEmail();

        $adminName = self::getAdminName();

        if (key_exists($type, self::MAIL_TITLES))
            $title = FHtml::t('common', self::MAIL_TITLES[$type]);
        else
            $title = FHtml::t('common', $type);

        if (is_object($content)) {
            $params = ['model' => $content];
            $content = FHtml::showObjectAsTable($content);
        } else if (is_array($content)) {
            $params = $content;
            $content = FHtml::showArrayAsTable($content);
        }

        //$content = "<div style='padding-top:20px; padding-bottom:20px'>$content</div>";
        $content = self::getContentHeader($type, $client_name) . $content;
        $footer = self::getContentFooter($type, $client_name);

        if (!empty($view))
            $params = array_merge($params, ['content' => $content, 'footer' => $footer]);

        $result = self::sendEmail($client_email, $client_name, $title, $content, $adminMail, $adminName, $view, $params);
        //echo $result; die;
        return $result;
    }

    public static function getContentHeader($type, $client_name) {
        $result = "Dear " . ucfirst($client_name) . ", <br/><br/>";
        $result .= "We already received your $type at " . FHtml::Today(FHtml::settingDateFormat()) . ". We will review and get back to you ASAP. <br/><br/>";

        return "<div style='font-size: 18px; padding-bottom: 20px; color:darkblue'> $result </div>";
    }

    public static function getContentFooter($type, $client_name) {
        $result = '';

        return $result;
    }


    private $_message;

    /**
     * Creates a new message instance and optionally composes its body content via view rendering.
     *
     * @param string|array|null $view the view to be used for rendering the message body. This can be:
     *
     * - a string, which represents the view name or path alias for rendering the HTML body of the email.
     *   In this case, the text body will be generated by applying `strip_tags()` to the HTML body.
     * - an array with 'html' and/or 'text' elements. The 'html' element refers to the view name or path alias
     *   for rendering the HTML body, while 'text' element is for rendering the text body. For example,
     *   `['html' => 'contact-html', 'text' => 'contact-text']`.
     * - null, meaning the message instance will be returned without body content.
     *
     * The view to be rendered can be specified in one of the following formats:
     *
     * - path alias (e.g. "@app/mail/contact");
     * - a relative view name (e.g. "contact") located under [[viewPath]].
     *
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return MessageInterface message instance.
     */
    private $content;
    public function getContent() {
        return $this->content;
    }

    public function compose($view = null, array $params = [])
    {
        $message = $this->createMessage();
        if ($view === null) {
            return $message;
        }

        if (!array_key_exists('message', $params)) {
            $params['message'] = $message;
        }

        $this->_message = $message;

        if (is_array($view)) {
            if (isset($view['html'])) {
                $html = $this->render($view['html'], $params, $this->htmlLayout);
            }
            if (isset($view['text'])) {
                $text = $this->render($view['text'], $params, $this->textLayout);
            }
        } else {
            $html = $this->render($view, $params, $this->htmlLayout);
        }


        $this->_message = null;

        if (isset($html)) {
            $this->content = $html;
            $message->setHtmlBody($html);
        }

        if (isset($text)) {
            if (empty($this->content))
                $this->content = $text;

            $message->setTextBody($text);
        } elseif (isset($html)) {
            if (preg_match('~<body[^>]*>(.*?)</body>~is', $html, $match)) {
                $html = $match[1];
            }
            // remove style and script
            $html = preg_replace('~<((style|script))[^>]*>(.*?)</\1>~is', '', $html);
            // strip all HTML tags and decoded HTML entities
            $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, Yii::$app ? Yii::$app->charset : 'UTF-8');
            // improve whitespace
            $text = preg_replace("~^[ \t]+~m", '', trim($text));
            $text = preg_replace('~\R\R+~mu', "\n\n", $text);
            $message->setTextBody($text);
        }

        return $message;
    }

    /**
     * Renders the specified view with optional parameters and layout.
     * The view will be rendered using the [[view]] component.
     * @param string $view the view name or the path alias of the view file.
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @param string|boolean $layout layout view name or path alias. If false, no layout will be applied.
     * @return string the rendering result.
     */
    public function render($view, $params = [], $layout = false)
    {
        $applicationId = FHtml::currentApplicationId();
        $root = FHtml::getRootFolder();
        $view = FHtml::strReplace($view, ['.php' => '', '@' => '']);
        $views = ["$root/applications/$applicationId/mail/$view.php", "$root/common/mail/$view.php"];
        $output = '';
        foreach ($views as $view1) {
            if (is_file($view1)) {
                $output = FHtmL::render($view1, $params, $this);
                break;
            }
        }
        if (empty($output)) {
            $output = $this->getView()->render($view, $params, $this);
        }

        if ($layout !== false) {
            $layout = FHtml::strReplace($layout, ['.php' => '', '@' => '']);
            $layouts = ["$root/applications/$applicationId/mail/$layout.php", "$root/common/mail/$layout.php"];
            foreach ($layouts as $layout1) {
                if (is_file($layout1)) {
                    return FHtml::render($layout1, ['content' => $output, 'message' => $this->_message], $this);
                }
            }

            return $this->getView()->render($layout, ['content' => $output, 'message' => $this->_message], $this);
        } else {
            return $output;
        }
    }


    /**
     * Sends the given email message.
     * This method will log a message about the email being sent.
     * If [[useFileTransport]] is true, it will save the email as a file under [[fileTransportPath]].
     * Otherwise, it will call [[sendMessage()]] to send the email to its recipient(s).
     * Child classes should implement [[sendMessage()]] with the actual email sending logic.
     * @param MessageInterface $message email message instance to be sent
     * @return boolean whether the message has been sent successfully
     */
    public function send($message)
    {
        if (!$this->beforeSend($message)) {
            return false;
        }

        $address = $message->getTo();
        if (is_array($address)) {
            $address = implode(', ', array_keys($address));
        }

        $to_Array = $message->getTo();
        $from_Array = $message->getFrom();

        if (is_array($from_Array) && !empty($from_Array)) {
            $from_email = array_keys($from_Array)[0];
            $from_name = array_values($from_Array)[0];
        } else {
            $from_email = FHtml::settingCompanyEmail();
            $from_name = FHtml::settingCompanyName();
        }
        $content = $this->getContent();

        // Send via API --> does not have to wait
        return static::sendEmail($message->getTo(), '', $message->getSubject(), $content, $from_email, $from_name);
    }

    public function sendDirect($message)
    {
        if (!$this->beforeSend($message)) {
            return false;
        }

        $address = $message->getTo();
        if (is_array($address)) {
            $address = implode(', ', array_keys($address));
        }
        Yii::info('Sending email "' . $message->getSubject() . '" to "' . $address . '"', __METHOD__);

        if ($this->useFileTransport) {
            $isSuccessful = $this->saveMessage($message);
        } else {
            $isSuccessful = $this->sendMessage($message);
        }

        $this->afterSend($message, $isSuccessful);

        return $isSuccessful;
    }

    public function sendNow($message) {
        return $this->sendDirect($message);
    }
}
