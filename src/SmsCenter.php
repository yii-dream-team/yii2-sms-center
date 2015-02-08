<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms;

use yii\base\Component as BaseComponent;
use yii\base\InvalidConfigException;
use yiidreamteam\sms\interfaces\SmsTransportInterface;
use yiidreamteam\sms\models\Message;

/**
 * Class SmsCenter
 * @package yiidreamteam\sms
 */
class SmsCenter extends BaseComponent implements SmsTransportInterface
{
    /** @var string */
    public $defaultTransport;
    /** @var SmsTransportInterface[] */
    public $transports = [];
    /** @var string */

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->transports))
            throw new InvalidConfigException('Missing sms transports');

        foreach ($this->transports as $name => $config)
            $this->transports[$name] = \Yii::createObject($config);

        if (empty($this->defaultTransport)) {
            reset($this->transports);
            $this->defaultTransport = key($this->transports);
        }
    }

    /**
     * Sends message immediately using default transport
     *
     * @param string $to
     * @param string $text
     * @param null|string $transport
     * @return bool
     */
    public function send($to, $text, $transport = null)
    {
        try {
            $result = $this->getTransport($transport)->send($to, $text);
        } catch (\Exception $e) {
            \Yii::error("Message to {$to} sending failure: " . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    /**
     * Get transport to perform some operation
     * If transport is not specified
     * will be used defaultTransport
     *
     * @param null $transport
     * @return SmsTransportInterface
     */
    public function getTransport($transport = null)
    {
        if (empty($transport))
            $transport = $this->defaultTransport;

        if (!array_key_exists($transport, $this->transports))
            throw new \BadMethodCallException('Bad transport ID');

        return $this->transports[$transport];
    }

    /**
     * Put message to queue
     *
     * @param $to
     * @param $text
     * @param int $priority
     * @param string|null $transport
     * @return bool
     */
    public function queue($to, $text, $priority = 0, $transport = null)
    {
        if (empty($transport))
            $transport = $this->defaultTransport;

        if (!array_key_exists($transport, $this->transports))
            throw new \BadMethodCallException('Bad transport ID');

        Message::queue($to, $text, $priority, $transport);

        return true;
    }
}