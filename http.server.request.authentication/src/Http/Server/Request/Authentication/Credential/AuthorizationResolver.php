<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server;
use Symsonte\Http\Server\Request\Authentication\AuthorizationCredential;

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
class AuthorizationResolver
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var AuthorizationCredential
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
     * @return AuthorizationCredential
     *
     * @throws UnresolvableException
     */
    public function resolve()
    {
        if (isset($this->credential)) {
            return $this->credential;
        }

        $headers = $this->server->resolveHeaders();

        if (!isset($headers['authorization']) || count($headers['authorization']) != 1) {
            throw new UnresolvableException();
        }

        $credential = new AuthorizationCredential(
            $headers['authorization'][0]
        );

        return $credential;
    }
}
