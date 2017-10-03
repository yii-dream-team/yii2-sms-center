<?php
/**
 * @author Yan Kuznetsov <info@yanman.com>
 */

namespace yiidreamteam\sms\transports;

use yii\base\Component;
use yiidreamteam\sms\interfaces\SmsTransportInterface;
use \YarCode\EpochtaSMS\Api;

class EpochtaSms extends Component implements SmsTransportInterface
{
    public $publicKey;
    public $privateKey;
    public $sender = null;
    public $sandbox = false; // sendSMS not work if sandbox is true

    /** @var Api */
    protected $api;

    /**
     * @inheritdoc
     */
    public function init()
    {
        assert(isset($this->privateKey));
        assert(isset($this->publicKey));

        $this->api = new Api($this->privateKey, $this->publicKey, $this->sandbox);
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param string $to
     * @param string $text
     * @return bool
     */
    public function send($to, $text)
    {
        try {
            $to = preg_replace('/[^0-9]/', '', $to);
            $result = $this->api->sendSMS($this->sender, $text, $to);

            return isset($result['result']);
        } catch (\Exception $e) {
            \Yii::error($e->getMessage(), 'sms.epochta');
            return false;
        }
    }
}