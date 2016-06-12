<?php

namespace Symsonte\Http\Response;

use Symsonte\Http\OrdinaryResponse;
use Symsonte\Http\ResponseSender;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class OrdinaryResponseSender implements ResponseSender
{
    public function support($response)
    {
        return $response instanceof OrdinaryResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function send($status, $headers, $body)
    {
        http_response_code($status);

        $this->sendHeaders($headers);

        echo $body;
    }

    private function sendHeaders($headers)
    {
        foreach ($headers as $key => $value) {
            header(sprintf('%s: %s', $key, $value), false);
        }
    }
}
