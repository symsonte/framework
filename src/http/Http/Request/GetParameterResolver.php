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
        $conversions = [];

        $get = $this->server->resolveQuery();

        if (!is_array($get)) {
            return $conversions;
        }

        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter['name'], $get)) {
                $conversions[$parameter['name']] = $get[$parameter['name']];

                unset($get[$parameter['name']]);
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
                $get
            );
        }

        return $conversions;
    }
}
