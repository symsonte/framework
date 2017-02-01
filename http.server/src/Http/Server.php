<?php

namespace Symsonte\Http;

use Symsonte\Http\Server\Request\DiactorosResolver as RequestResolver;
use Symsonte\Http\Server\Request\UriModifier as RequestUriModifier;
use Symsonte\Http\Server\Request\BodyModifier as RequestBodyModifier;
use Symsonte\Http\Server\Request\HeadersModifier as RequestHeadersModifier;
use Symsonte\Http\Server\Response\Sender as ResponseSender;
use Symsonte\Http\Server\Response\BodyModifier as ResponseBodyModifier;
use Symsonte\Http\Server\Response\HeadersModifier as ResponseHeadersModifier;

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
     * @var RequestUriModifier[]
     */
    private $requestUriModifiers;
    
    /**
     * @var RequestHeadersModifier[]
     */
    private $requestHeadersModifiers;
    
    /**
     * @var RequestBodyModifier[]
     */
    private $requestBodyModifiers;

    /**
     * @var ResponseHeadersModifier[]
     */
    private $responseHeadersModifiers;

    /**
     * @var ResponseBodyModifier[]
     */
    private $responseBodyModifiers;

    /**
     * @var array
     */
    private $method;

    /**
     * @var array
     */
    private $uri;

    /**
     * @var array
     */
    private $version;
    
    /**
     * @var array
     */
    private $headers;
    
    /**
     * @var mixed
     */
    private $body;
    
    /**
     * @param RequestResolver                $requestResolver
     * @param ResponseSender                 $responseSender
     * @param RequestUriModifier[]|null      $requestUriModifiers
     * @param RequestHeadersModifier[]|null  $requestHeadersModifiers
     * @param RequestBodyModifier[]|null     $requestBodyModifiers
     * @param ResponseHeadersModifier[]|null $responseHeadersModifiers
     * @param ResponseBodyModifier[]|null    $responseBodyModifiers
     *
     * @ds\arguments({
     *     requestResolver:          '@symsonte.http.server.request.diactoros_resolver',
     *     responseSender:           '@symsonte.http.server.response.diactoros_sender',
     *     requestUriModifiers:      '#symsonte.http.server.request.uri_modifier',
     *     requestHeadersModifiers:  '#symsonte.http.server.request.headers_modifier',
     *     requestBodyModifiers:     '#symsonte.http.server.request.body_modifier',
     *     responseHeadersModifiers: '#symsonte.http.server.response.headers_modifier',
     *     responseBodyModifiers:    '#symsonte.http.server.response.body_modifier',
     * })
     *
     * @di\arguments({
     *     requestResolver:          '@symsonte.http.server.request.diactoros_resolver',
     *     responseSender:           '@symsonte.http.server.response.diactoros_sender',
     *     requestUriModifiers:      '#symsonte.http.server.request.uri_modifier',
     *     requestHeadersModifiers:  '#symsonte.http.server.request.headers_modifier',
     *     requestBodyModifiers:     '#symsonte.http.server.request.body_modifier',
     *     responseHeadersModifiers: '#symsonte.http.server.response.headers_modifier',
     *     responseBodyModifiers:    '#symsonte.http.server.response.body_modifier',
     * })
     */
    function __construct(
        RequestResolver $requestResolver,
        ResponseSender $responseSender,
        array $requestUriModifiers = null,
        array $requestHeadersModifiers = null,
        array $requestBodyModifiers = null,
        array $responseHeadersModifiers = null,
        array $responseBodyModifiers = null
    )
    {
        $this->requestResolver = $requestResolver;
        $this->responseSender = $responseSender;
        $this->requestUriModifiers = $requestUriModifiers ?: [];
        $this->requestHeadersModifiers = $requestHeadersModifiers ?: [];
        $this->requestBodyModifiers = $requestBodyModifiers ?: [];
        $this->responseHeadersModifiers = $responseHeadersModifiers ?: [];
        $this->responseBodyModifiers = $responseBodyModifiers ?: [];
    }

    /**
     * @return array
     */
    public function resolveMethod()
    {
        return $this->requestResolver->resolveMethod();
    }

    /**
     * @return array
     */
    public function resolveUri()
    {
        if (is_null($this->uri)) {
            $method = $this->requestResolver->resolveHeaders();
            $this->uri = $this->requestResolver->resolveUri()->__toString();
            $version = $this->requestResolver->resolveVersion();
            $headers = $this->requestResolver->resolveHeaders();
            $body = $this->requestResolver->resolveBody()->getContents();

            foreach ($this->requestUriModifiers as $uriModifier) {
                $this->uri = $uriModifier->modify($method, $this->uri, $version, $headers, $body);
            }
        }

        return $this->uri;
    }

    /**
     * @return array
     */
    public function resolverVersion()
    {
        return $this->requestResolver->resolveVersion();
    }

    /**
     * @return array
     */
    public function resolveHeaders()
    {
        if (is_null($this->headers)) {
            $method = $this->requestResolver->resolveHeaders();
            $uri = $this->requestResolver->resolveUri()->__toString();
            $version = $this->requestResolver->resolveVersion();
            $this->headers = $this->requestResolver->resolveHeaders();
            $body = $this->requestResolver->resolveBody()->getContents();

            foreach ($this->requestHeadersModifiers as $headersModifier) {
                $this->headers = $headersModifier->modify($method, $uri, $version, $this->headers, $body);
            }
        }

        return $this->headers;
    }
    
    /**
     * @return mixed
     */
    public function resolveBody()
    {
        if (is_null($this->body)) {
            $method = $this->requestResolver->resolveHeaders();
            $uri = $this->requestResolver->resolveUri()->__toString();
            $version = $this->requestResolver->resolveVersion();
            $headers = $this->requestResolver->resolveHeaders();
            $this->body = $this->requestResolver->resolveBody()->getContents();

            foreach ($this->requestBodyModifiers as $bodyModifier) {
                $this->body = $bodyModifier->modify($method, $uri, $version, $headers, $this->body);
            }
        }
        
        return $this->body;
    }

    /**
     * @param mixed  $body
     * @param string $status
     * @param array  $headers
     */
    public function sendResponse($body = '', $status = '200', $headers = array())
    {
        foreach ($this->responseHeadersModifiers as $headerModifier) {
            $headers = $headerModifier->modify($status, $headers, $body);
        }

        foreach ($this->responseBodyModifiers as $bodyModifier) {
            $body = $bodyModifier->modify($status, $headers, $body);
        }

        $this->responseSender->send($status, $headers, $body);
    }
}
