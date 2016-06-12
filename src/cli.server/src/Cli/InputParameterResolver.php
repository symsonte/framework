<?php

namespace Symsonte\Cli;

use Symsonte\Call\ParameterResolver;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_resolver']
 * })
 */
class InputParameterResolver implements ParameterResolver
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @param Server $server
     */
    public function __construct(
        Server $server
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

        $i = 2;
        $input = [];
        while ($this->server->resolveInput()->get($i) !== null) {
            $input[] = $this->server->resolveInput()->get($i);
            $i++;
        }

        if (!is_array($input)) {
            return $convertions;
        }

        foreach ($parameters as $key => $value) {
            if (array_key_exists($key, $input)) {
                $convertions[$value] = $input[$key];
            }
        }

        return $convertions;
    }
}
