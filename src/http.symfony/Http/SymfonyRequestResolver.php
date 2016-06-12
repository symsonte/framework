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
    private array $trustedProxies;

    private ?HttpFoundation\Request $request;

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
        $this->request = null;
    }

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
        return $this->resolveRequest()->getPathInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveQuery(): ?string
    {
        return $this->resolveRequest()->getQueryString();
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
        return $this->resolveRequest()->headers->all();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBody(): string|null|false
    {
        return $this->resolveRequest()->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveParsedBody(): string|null|false
    {
        return $this->resolveRequest()->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveIp(): ?string
    {
        return $this->resolveRequest()->getClientIp();
    }

    private function resolveRequest(): HttpFoundation\Request
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
