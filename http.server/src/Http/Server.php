<?php

namespace Symsonte\Http;

use Symsonte\Http\Server\Request\BodyModifier as RequestBodyModifier;
use Symsonte\Http\Server\Request\DiactorosResolver as RequestResolver;
use Symsonte\Http\Server\Request\HeadersModifier as RequestHeadersModifier;
use Symsonte\Http\Server\Request\UriModifier as RequestUriModifier;
use Symsonte\Http\Server\Response\BodyModifier as ResponseBodyModifier;
use Symsonte\Http\Server\Response\HeadersModifier as ResponseHeadersModifier;
use Symsonte\Http\Server\Response\Sender as ResponseSender;

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
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $uri;

    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $parsedBody;

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
    public function __construct(
        RequestResolver $requestResolver,
        ResponseSender $responseSender,
        array $requestUriModifiers = null,
        array $requestHeadersModifiers = null,
        array $requestBodyModifiers = null,
        array $responseHeadersModifiers = null,
        array $responseBodyModifiers = null
    ) {
        $this->requestResolver = $requestResolver;
        $this->responseSender = $responseSender;
        $this->requestUriModifiers = $requestUriModifiers ?: [];
        $this->requestHeadersModifiers = $requestHeadersModifiers ?: [];
        $this->requestBodyModifiers = $requestBodyModifiers ?: [];
        $this->responseHeadersModifiers = $responseHeadersModifiers ?: [];
        $this->responseBodyModifiers = $responseBodyModifiers ?: [];
    }

    /**
     * @return string
     */
    public function resolveMethod()
    {
        if (is_null($this->method)) {
            $this->resolveAll();
        }

        return $this->method;
    }

    /**
     * @return array
     */
    public function resolveUri()
    {
        if (is_null($this->uri)) {
            $this->resolveAll();
        }

        return $this->uri;
    }

    /**
     * @return string
     */
    public function resolveVersion()
    {
        if (is_null($this->version)) {
            $this->resolveAll();
        }

        return $this->version;
    }

    /**
     * @return array
     */
    public function resolveHeaders()
    {
        if (is_null($this->headers)) {
            $this->resolveAll();
        }

        return $this->headers;
    }

    /**
     * @return string
     */
    public function resolveBody()
    {
        if (is_null($this->body)) {
            $this->resolveAll();
        }

        return $this->body;
    }

    /**
     * @return array
     */
    public function resolveParsedBody()
    {
        if (is_null($this->parsedBody)) {
            $this->resolveAll();
        }

        return $this->parsedBody;
    }

    /**
     * @param mixed  $body
     * @param string $status
     * @param array  $headers
     */
    public function sendResponse($body = '', $status = '200', $headers = [])
    {
        foreach ($this->responseHeadersModifiers as $headerModifier) {
            $headers = $headerModifier->modify($status, $headers, $body);
        }

        foreach ($this->responseBodyModifiers as $bodyModifier) {
            $body = $bodyModifier->modify($status, $headers, $body);
        }

        $this->responseSender->send($status, $headers, $body);
    }

    private function resolveAll()
    {
        $this->method = $this->requestResolver->resolveMethod();
        $this->uri = $this->requestResolver->resolveUri()->__toString();
        $this->version = $this->requestResolver->resolveVersion();
        $this->headers = $this->requestResolver->resolveHeaders();
        $this->body = $this->requestResolver->resolveBody()->getContents();
        $this->parsedBody = $this->requestResolver->resolveParsedBody();

        foreach ($this->requestHeadersModifiers as $headersModifier) {
            $this->headers = $headersModifier->modify(
                $this->method,
                $this->uri,
                $this->version,
                $this->headers,
                $this->body
            );
        }

        foreach ($this->requestUriModifiers as $uriModifier) {
            $this->uri = $uriModifier->modify(
                $this->method,
                $this->uri,
                $this->version,
                $this->headers,
                $this->body
            );
        }

        foreach ($this->requestBodyModifiers as $bodyModifier) {
            $this->body = $bodyModifier->modify(
                $this->method,
                $this->uri,
                $this->version,
                $this->headers,
                $this->body
            );
        }
    }
}
