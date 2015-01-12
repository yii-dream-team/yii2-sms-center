<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms\interfaces;

interface TransportInterface
{
    /**
     * @param string $to
     * @param string $text
     * @return mixed
     */
    public function send($to, $text);
}