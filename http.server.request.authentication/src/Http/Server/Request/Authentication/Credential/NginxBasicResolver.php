<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server;
use Symsonte\Http\Server\Request\Authentication\Credential;

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
class NginxBasicResolver implements Resolver
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var Credential
     */
    private $credential;

    /**
     * @param Server $server
     *
     * @ds\arguments({
     *     server: '@symsonte.http.server'
     * })
     *
     * @di\arguments({
     *     server: '@symsonte.http.server'
     * })
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
        if (isset($this->credential)) {
            return $this->credential;
        }

        $request = $this->server->resolveRequest();

        if (
            $request->hasHeader('PHP_AUTH_USER') === false
            || $request->hasHeader('PHP_AUTH_PW') === false
        ) {
            throw new UnresolvableException();
        }

        $credential = new Credential(
            $request->getHeader('PHP_AUTH_USER'),
            $request->getHeader('PHP_AUTH_PW')
        );

        return $credential;
    }
}
