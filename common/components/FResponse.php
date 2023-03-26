<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-31
 * Time: 08:53
 */
namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\web\ResponseFormatterInterface;

class FResponse extends \yii\web\Response
{
    /**
     * Prepares for sending the response.
     * The default implementation will convert [[data]] into [[content]] and set headers accordingly.
     * @throws InvalidConfigException if the formatter for the specified format is invalid or [[format]] is not supported
     */
    protected function prepare()
    {
        if ($this->stream !== null) {
            return;
        }

        if (is_array($this->data)) {
            $this->format = Response::FORMAT_JSON;
        }

        if (isset($this->formatters[$this->format])) {
            $formatter = $this->formatters[$this->format];
            if (!is_object($formatter)) {
                $this->formatters[$this->format] = $formatter = Yii::createObject($formatter);
            }
            if ($formatter instanceof ResponseFormatterInterface) {
                $formatter->format($this);
            } else {
                throw new InvalidConfigException("The '{$this->format}' response formatter is invalid. It must implement the ResponseFormatterInterface.");
            }
        } elseif ($this->format === self::FORMAT_RAW) {
            if ($this->data !== null) {
                $this->content = $this->data;
            }
        } else {
            throw new InvalidConfigException("Unsupported response format: {$this->format}");
        }

        if (is_array($this->content)) {
            echo '[FResponse] Response content must not be an array.';

            FHtml::var_dump($this->content);
            //throw new InvalidParamException('Response content must not be an array.');
        } elseif (is_object($this->content)) {
            if (method_exists($this->content, '__toString')) {
                $this->content = $this->content->__toString();
            } else if (method_exists($this->content, 'send')) {
                return $this->content->send();
            } else {
                echo '[FResponse] Response content must be a string or an object implementing __toString().';
                FHtml::var_dump($this->content);
                $this->content = '';
                //throw new InvalidParamException('Response content must be a string or an object implementing __toString().');
            }
        }
    }
}