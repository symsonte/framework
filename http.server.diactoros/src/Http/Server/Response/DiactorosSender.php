<?php

namespace Symsonte\Http\Server\Response;

use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Stream;

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
class DiactorosSender implements Sender
{
    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @param EmitterInterface|null $emitter
     */
    public function __construct(EmitterInterface $emitter = null)
    {
        $this->emitter = $emitter ?: new SapiEmitter();
    }

    /**
     * {@inheritdoc}
     */
    public function send($status, $headers, $body)
    {
        $stream = new Stream('php://temp', 'wb+');
        $stream->write($body);
        $stream->rewind();

        $response = new Response($stream, $status, $headers);

        $this->emitter->emit($response);
    }
}
