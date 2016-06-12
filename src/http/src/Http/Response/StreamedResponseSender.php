<?php

namespace Symsonte\Http\Response;

use Symsonte\Http\ResponseSender;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class StreamedResponseSender implements ResponseSender
{
    /**
     * {@inheritdoc}
     */
    public function send($status, $headers, $body)
    {
        http_response_code($status);

        $this->sendHeaders($headers);

        call_user_func($body);
    }

    private function sendHeaders($headers)
    {
        foreach ($headers as $key => $value) {
            header(sprintf('%s: %s', $key, $value), false);
        }
    }
}
