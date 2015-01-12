<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms\transports;

use yii\base\Component;
use yiidreamteam\sms\interfaces\TransportInterface;
use yiidreamteam\smsru\Api;

class SmsRu extends Component implements TransportInterface
{
    public $apiId;
    public $sender = null;

    /** @var Api */
    protected $api;

    public function init()
    {
        $this->_api = new Api($this->apiId);
    }

    public function getApi()
    {
        return $this->api;
    }

    public function send($to, $text)
    {
        $result = $this->api->send($to, $text, $this->sender);
        return $result['code'] == '100';
    }
}