<?php

namespace Symsonte\Http\Server\Response;

use Symsonte\Http\Server\StreamedResponse;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class StreamedSender implements Sender
{
    public function support($response)
    {
        return $response instanceof StreamedResponse;
    }

    /**
     * @param \Symsonte\Http\Server\StreamedResponse $response
     */
    public function send($response)
    {
        $this->sendHeaders($response->getHeaders());

        call_user_func($response->getContentCallback());
    }

    private function sendHeaders($headers)
    {
        foreach ($headers as $key => $value) {
            header(sprintf("%s: %s", $key, $value), false);
        }
    }
}
