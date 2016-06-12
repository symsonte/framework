<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\Parameter\ResolutionStorer;
use Symsonte\Call\ParameterResolver;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_resolver']
 * })
 */
class DeviceParameterResolver implements ParameterResolver
{
    /**
     * @var ResolutionStorer
     */
    private $resolutionStorer;

    /**
     * @var Http\Server
     */
    private $server;

    /**
     * @param ResolutionStorer $resolutionStorer
     * @param Http\Server      $server
     */
    public function __construct(
        ResolutionStorer $resolutionStorer,
        Http\Server $server
    ) {
        $this->resolutionStorer = $resolutionStorer;
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
        
        $key = $this->resolutionStorer->find(
            $class,
            $method,
            "http\\request\\device"
        );

        if (!$key) {
            return $conversions;
        }

        $headers = $this->server->resolveHeaders();

        if (!isset($headers['device'])) {
            return $conversions;
        }

        $conversions[$key] = $headers['device'][0];

        return $conversions;
    }
}
