<?php

namespace Symsonte\Http;

use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

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
class DiactorosRequestResolver implements RequestResolver
{
    /**
     * @var ServerRequest
     */
    private ServerRequest $request;

    /**
     * {@inheritdoc}
     */
    public function resolveMethod(): string
    {
        return $this->resolveRequest()->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePath(): string
    {
        return $this->resolveRequest()->getUri()->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveQuery(): string
    {
        return $this->resolveRequest()->getUri()->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveVersion(): ?string
    {
        return $this->resolveRequest()->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveHeaders(): array
    {
        return $this->resolveRequest()->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBody(): StreamInterface
    {
        return $this->resolveRequest()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveParsedBody(): string
    {
        return $this->resolveRequest()->getParsedBody();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveIp(): ?string
    {
        throw new \LogicException("Not implemented");
    }

    /**
     * @return ServerRequest
     */
    private function resolveRequest(): ServerRequest
    {
        if (is_null($this->request)) {
            $this->request = ServerRequestFactory::fromGlobals();
        }

        return $this->request;
    }
}
