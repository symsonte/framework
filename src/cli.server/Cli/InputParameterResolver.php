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
        $conversions = [];

        $i = 2;
        $input = [];
        while ($this->server->resolveInput()->get($i) !== null) {
            $input[] = $this->server->resolveInput()->get($i);
            $i++;
        }

        if (!is_array($input)) {
            return $conversions;
        }

        foreach ($parameters as $key => $parameter) {
            if (array_key_exists($key, $input)) {
                $conversions[$parameter['name']] = $input[$key];

                unset($input[$key]);
            }
        }

        /* Handle variadic parameter: ...$payload */

        foreach ($parameters as $parameter) {
            if (!$parameter['variadic']) {
                continue;
            }

            // Merge the rest of $get to the parameter
            $conversions = array_merge(
                $conversions,
                $input
            );
        }

        return $conversions;
    }
}
