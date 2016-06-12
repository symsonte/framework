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
        $conversions = [];

        $post = $this->server->resolveBody();

        if (!is_array($post)) {
            return $conversions;
        }

        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter['name'], $post)) {
                $conversions[$parameter['name']] = $post[$parameter['name']];

                unset($post[$parameter['name']]);
            }
        }

        /* Handle variadic parameter: ...$payload */

        foreach ($parameters as $parameter) {
            if (!$parameter['variadic']) {
                continue;
            }

            // Merge the rest of $post to the parameter
            $conversions = array_merge(
                $conversions,
                $post
            );
        }

        return $conversions;
    }
}
