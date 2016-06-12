<?php

namespace Symsonte\Http;

use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\Response;

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
class DiactorosResponseSender implements ResponseSender
{
    /**
     * {@inheritdoc}
     */
    public function send($status, $headers, $body)
    {
        $stream = (new StreamFactory)->createStream();
        $stream->write($body);
        $stream->rewind();

        $response = new Response($stream, $status, $headers);

        // TODO
    }
}
