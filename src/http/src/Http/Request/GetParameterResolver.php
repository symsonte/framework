<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\ParameterResolver;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_resolver']
 * })
 */
class GetParameterResolver implements ParameterResolver
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

        $get = $this->server->resolveQuery();

        if (!is_array($get)) {
            return $convertions;
        }

        foreach ($parameters as $key) {
            if (array_key_exists($key, $get)) {
                $convertions[$key] = $get[$key];
            }
        }

        return $convertions;
    }
}
