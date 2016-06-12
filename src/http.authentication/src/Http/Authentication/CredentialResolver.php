<?php

namespace Symsonte\Http\Authentication;

use Symsonte\Http\Server;
use Symsonte\Authentication;

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
class CredentialResolver implements Authentication\CredentialResolver
{
    /**
     * @var Server
     */
    private $server;

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
     * @throws Authentication\UnresolvableCredentialException
     *
     * @return Authentication\Credential
     */
    public function resolve()
    {
        $headers = $this->server->resolveHeaders();

        if (!isset($headers['authorization']) || count($headers['authorization']) != 1) {
            throw new Authentication\UnresolvableCredentialException();
        }

        $credential = new Authentication\Credential(
            $headers['authorization'][0]
        );

        return $credential;
    }
}
