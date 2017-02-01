<?php

namespace Symsonte\Http\Server\Request;

use Zend\Diactoros\Request;
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
class DiactorosResolver implements Resolver
{
    /**
     * @var Request
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
    public function resolveUri()
    {
        return $this->resolveRequest()->getUri();
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
     * @return Request
     */
    private function resolveRequest()
    {
        if (is_null($this->request)) {
            $this->request = ServerRequestFactory::fromGlobals();
        }

        return $this->request;
    }
}
