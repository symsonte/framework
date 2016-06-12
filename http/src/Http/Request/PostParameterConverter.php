<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\ParameterConverter;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_converter']
 * })
 */
class PostParameterConverter implements ParameterConverter
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
     * @param string  $class
     * @param string  $method
     * @param mixed[] $parameters
     *
     * @return array
     */
    public function convert(
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
