<?php

namespace Symsonte\Http;

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
    private $request;

    /**
     * {@inheritdoc}
     */
    public function resolveMethod()
    {
        return $this->resolveRequest()->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePath()
    {
        return $this->resolveRequest()->getUri()->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveQuery()
    {
        return $this->resolveRequest()->getUri()->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveVersion()
    {
        return $this->resolveRequest()->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveHeaders()
    {
        return $this->resolveRequest()->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBody()
    {
        return $this->resolveRequest()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveParsedBody()
    {
        return $this->resolveRequest()->getParsedBody();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveIp()
    {
        throw new \LogicException("Not implemented");
    }

    /**
     * @return ServerRequest
     */
    private function resolveRequest()
    {
        if (is_null($this->request)) {
            $this->request = ServerRequestFactory::fromGlobals();
        }

        return $this->request;
    }
}
