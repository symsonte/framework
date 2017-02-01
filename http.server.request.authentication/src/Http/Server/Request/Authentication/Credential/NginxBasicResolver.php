<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server;
use Symsonte\Http\Server\Request\Authentication\BasicCredential;

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
class NginxBasicResolver
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var BasicCredential
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
     * @throws UnresolvableException
     *
     * @return BasicCredential
     */
    public function resolve()
    {
        if (isset($this->credential)) {
            return $this->credential;
        }

        $headers = $this->server->resolveHeaders();

        if (
            isset($headers['PHP_AUTH_USER'])
            || isset($headers['PHP_AUTH_PW'])
        ) {
            throw new UnresolvableException();
        }

        $credential = new BasicCredential(
            $headers['PHP_AUTH_USER'],
            $headers['PHP_AUTH_PW']
        );

        return $credential;
    }
}
