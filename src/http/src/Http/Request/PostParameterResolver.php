<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\ParameterResolver;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_resolver']
 * })
 */
class PostParameterResolver implements ParameterResolver
{
    /**
     * @var Http\Server
     */
    private $server;

    /**
     * @param Http\Server $server
     */
    public function __construct(
        Http\Server $server
    ) {
        $this->server = $server;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(
        string $class,
        string $method,
        array $parameters
    ) {
        $convertions = [];

        $post = $this->server->resolveBody();

        if (!is_array($post)) {
            return $convertions;
        }

        foreach ($parameters as $key) {
            if (array_key_exists($key, $post)) {
                $convertions[$key] = $post[$key];
            }
        }

        return $convertions;
    }
}
