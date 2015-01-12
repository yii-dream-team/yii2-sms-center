<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms;

use yii\base\Component as BaseComponent;
use yii\base\InvalidConfigException;
use yiidreamteam\sms\interfaces\TransportInterface;

class SmsCenter extends BaseComponent implements TransportInterface
{
    /** @var string */
    public $defaultTransport;
    /** @var TransportInterface[] */
    public $transports = [];

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
     * @return bool
     */
    public function send($to, $text)
    {
        try {
            $result = $this->transports[$this->defaultTransport]->send($to, $text);
        } catch (\Exception $e) {
            $result = false;
        }
        return $result;
    }
}