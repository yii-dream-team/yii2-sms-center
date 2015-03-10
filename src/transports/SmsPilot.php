<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms\transports;

use yii\base\Component;
use yii\helpers\VarDumper;
use yiidreamteam\sms\interfaces\SmsTransportInterface;
use yiidreamteam\smspilot\Api;

class SmsPilot extends Component implements SmsTransportInterface
{
    public $apiKey;
    public $sender = null;

    public $sandbox = false;

    /** @var Api */
    protected $api;

    public function init()
    {
        assert(isset($this->apiKey));

        $this->api = new Api($this->apiKey);
        $this->api->sandbox = $this->sandbox;
    }

    public function getApi()
    {
        return $this->api;
    }

    public function send($to, $text)
    {
        try {
            $to = preg_replace('/[^0-9]/', '', $to);
            $result = $this->api->send($to, $text, $this->sender);
            if ($result['send'][0]['status'] == 0) {
                return true;
            } else {
                \Yii::error('SmsPilot error ' . $result['send'][0]['status'], 'sms.smspilot');
                \Yii::trace(VarDumper::dumpAsString($result['send']), 'sms.smspilot');
                return false;
            }
        } catch (\Exception $e) {
            \Yii::error($e->getMessage(), 'sms.smspilot');
            return false;
        }
    }
}