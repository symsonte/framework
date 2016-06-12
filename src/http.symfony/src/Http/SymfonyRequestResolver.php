<?php

namespace Symsonte\Http;

use Symfony\Component\HttpFoundation;

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
class SymfonyRequestResolver implements RequestResolver
{
    /**
     * @var array
     */
    private $trustedProxies;

    /**
     * @var HttpFoundation\Request
     */
    private $request;

    /**
     * @di\arguments({
     *     trustedProxies: "%trusted_proxies%"
     * })
     *
     * @param array $trustedProxies
     */
    public function __construct(array $trustedProxies)
    {
        $this->trustedProxies = $trustedProxies;
    }

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
        return $this->resolveRequest()->getPathInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveQuery()
    {
        return $this->resolveRequest()->getQueryString();
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
        return $this->resolveRequest()->headers->all();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBody()
    {
        return $this->resolveRequest()->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveParsedBody()
    {
        return $this->resolveRequest()->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveIp()
    {
        return $this->resolveRequest()->getClientIp();
    }

    /**
     * @return HttpFoundation\Request
     */
    private function resolveRequest()
    {
        if (is_null($this->request)) {
            $this->request = HttpFoundation\Request::createFromGlobals();
            $this->request::setTrustedProxies(
                $this->trustedProxies,
                HttpFoundation\Request::HEADER_X_FORWARDED_FOR
            );
        }

        return $this->request;
    }
}
