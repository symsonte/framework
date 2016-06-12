<?php

namespace Symsonte\Http;

use Symsonte\Http\Server\Request\Resolver as RequestResolver;
use Symsonte\Http\Server\Request\Modifier as RequestModifier;
use Symsonte\Http\Server\Response\Sender as ResponseSender;
use Symsonte\Http\Server\GetRequest;
use Symsonte\Http\Server\PostRequest;
use Symsonte\Http\Server\Response\Modifier as ResponseModifier;

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
class Server
{
    /**
     * @var RequestResolver
     */
    private $requestResolver;

    /**
     * @var ResponseSender
     */
    private $responseSender;

    /**
     * @var RequestModifier[]
     */
    private $requestModifiers;

    /**
     * @var ResponseModifier[]
     */
    private $responseModifiers;

    /**
     * @var GetRequest|PostRequest
     */
    private $request;
    
    /**
     * @param RequestResolver         $requestResolver
     * @param ResponseSender          $responseSender
     * @param RequestModifier[]|null  $requestModifiers
     * @param ResponseModifier[]|null $responseModifiers
     *
     * @ds\arguments({
     *     requestResolver:   '@symsonte.http.server.request.resolver',
     *     responseSender:    '@symsonte.http.server.response.ordinary_sender',
     *     requestModifiers:  '#symsonte.http.server.request.modifier',
     *     responseModifiers: '#symsonte.http.server.response.modifier'
     * })
     *
     * @di\arguments({
     *     requestResolver:   '@symsonte.http.server.request.resolver',
     *     responseSender:    '@symsonte.http.server.response.ordinary_sender',
     *     requestModifiers:  '#symsonte.http.server.request.modifier',
     *     responseModifiers: '#symsonte.http.server.response.modifier'
     * })
     */
    function __construct(
        RequestResolver $requestResolver,
        ResponseSender $responseSender,
        array $requestModifiers = null,
        array $responseModifiers = null
    )
    {
        $this->requestResolver = $requestResolver;
        $this->responseSender = $responseSender;
        $this->requestModifiers = $requestModifiers ?: [];
        $this->responseModifiers = $responseModifiers ?: [];
    }

    /**
     * @return GetRequest|PostRequest
     */
    public function resolveRequest()
    {
        if (!$this->request) {
            $this->request = $this->requestResolver->resolve();
        }

        foreach ($this->requestModifiers as $modifier) {
            $this->request = $modifier->modify($this->request);
        }
        
        return $this->request;
    }

    /**
     * @param $response
     */
    public function sendResponse($response)
    {
        foreach ($this->responseModifiers as $modifier) {
            $response = $modifier->modify($response);
        }

        $this->responseSender->send($response);
    }
}
