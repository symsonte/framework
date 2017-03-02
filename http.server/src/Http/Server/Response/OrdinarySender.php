<?php

namespace Symsonte\Http\Server\Response;

use Symsonte\Http\Server\OrdinaryResponse;

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
class OrdinarySender implements Sender
{
    public function support($response)
    {
        return $response instanceof OrdinaryResponse;
    }

    /**
     * @param \Symsonte\Http\Server\OrdinaryResponse $response
     */
    public function send($response)
    {
        http_response_code($response->getStatus());

        $this->sendHeaders($response->getHeaders());

        echo $response->getContent();
    }

    private function sendHeaders($headers)
    {
        foreach ($headers as $key => $value) {
            header(sprintf('%s: %s', $key, $value), false);
        }
    }
}
