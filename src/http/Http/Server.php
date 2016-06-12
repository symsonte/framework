<?php

namespace Symsonte\Http;

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
     * @var Request\PathModifier[]
     */
    private $requestPathModifiers;

    /**
     * @var Request\QueryModifier[]
     */
    private $requestQueryModifiers;

    /**
     * @var Request\HeadersModifier[]
     */
    private $requestHeadersModifiers;

    /**
     * @var Request\BodyModifier[]
     */
    private $requestBodyModifiers;

    /**
     * @var Response\HeadersModifier[]
     */
    private $responseHeadersModifiers;

    /**
     * @var Response\BodyModifier[]
     */
    private $responseBodyModifiers;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $query;
    /**
     * @var string
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
     * @var array
     */
    private $parsedBody;

    /**
     * @var string
     */
    private $ip;

    /**
     * @param RequestResolver                $requestResolver
     * @param ResponseSender                 $responseSender
     * @param Request\PathModifier[]|null     $requestPathModifiers
     * @param Request\QueryModifier[]|null    $requestQueryModifiers
     * @param Request\HeadersModifier[]|null  $requestHeadersModifiers
     * @param Request\BodyModifier[]|null     $requestBodyModifiers
     * @param Response\HeadersModifier[]|null $responseHeadersModifiers
     * @param Response\BodyModifier[]|null    $responseBodyModifiers
     *
     * @ds\arguments({
     *     requestResolver:          '@symsonte.http.symfony_request_resolver',
     *     responseSender:           '@symsonte.http.response_sender',
     *     requestPathModifiers:     '#symsonte.http.request.path_modifier',
     *     requestQueryModifiers:    '#symsonte.http.request.query_modifier',
     *     requestHeadersModifiers:  '#symsonte.http.request.headers_modifier',
     *     requestBodyModifiers:     '#symsonte.http.request.body_modifier',
     *     responseHeadersModifiers: '#symsonte.http.response.headers_modifier',
     *     responseBodyModifiers:    '#symsonte.http.response.body_modifier',
     * })
     *
     * @di\arguments({
     *     requestResolver:          '@symsonte.http.symfony_request_resolver',
     *     responseSender:           '@symsonte.http.response_sender',
     *     requestPathModifiers:     '#symsonte.http.request.path_modifier',
     *     requestQueryModifiers:    '#symsonte.http.request.query_modifier',
     *     requestHeadersModifiers:  '#symsonte.http.request.headers_modifier',
     *     requestBodyModifiers:     '#symsonte.http.request.body_modifier',
     *     responseHeadersModifiers: '#symsonte.http.response.headers_modifier',
     *     responseBodyModifiers:    '#symsonte.http.response.body_modifier',
     * })
     */
    public function __construct(
        RequestResolver $requestResolver,
        ResponseSender $responseSender,
        array $requestPathModifiers = null,
        array $requestQueryModifiers = null,
        array $requestHeadersModifiers = null,
        array $requestBodyModifiers = null,
        array $responseHeadersModifiers = null,
        array $responseBodyModifiers = null
    ) {
        $this->requestResolver = $requestResolver;
        $this->responseSender = $responseSender;
        $this->requestPathModifiers = $requestPathModifiers ?: [];
        $this->requestQueryModifiers = $requestQueryModifiers ?: [];
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
     * @return string
     */
    public function resolvePath()
    {
        if (is_null($this->path)) {
            $this->resolveAll();
        }

        return $this->path;
    }

    /**
     * @return array
     */
    public function resolveQuery()
    {
        if (is_null($this->query)) {
            $this->resolveAll();
        }

        return $this->query;
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
     * @return mixed
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
     * @return string
     */
    public function resolveIp()
    {
        if (is_null($this->ip)) {
            $this->resolveAll();
        }

        return $this->ip;
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
        $this->path = $this->requestResolver->resolvePath();
        parse_str($this->requestResolver->resolveQuery(), $this->query);
        $this->version = $this->requestResolver->resolveVersion();
        $this->headers = $this->requestResolver->resolveHeaders();
        $this->body = $this->requestResolver->resolveBody();
        $this->parsedBody = $this->requestResolver->resolveParsedBody();
        $this->ip = $this->requestResolver->resolveIp();

        foreach ($this->requestHeadersModifiers as $headersModifier) {
            $this->headers = $headersModifier->modify(
                $this->method,
                $this->path,
                $this->query,
                $this->version,
                $this->headers,
                $this->body
            );
        }

        foreach ($this->requestPathModifiers as $pathModifier) {
            $this->path = $pathModifier->modify(
                $this->method,
                $this->path,
                $this->query,
                $this->version,
                $this->headers,
                $this->body
            );
        }

        foreach ($this->requestBodyModifiers as $bodyModifier) {
            $this->body = $bodyModifier->modify(
                $this->method,
                $this->path,
                $this->query,
                $this->version,
                $this->headers,
                $this->body
            );
        }
    }
}
