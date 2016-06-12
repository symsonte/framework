<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\Parameter\ConvertionStorer;
use Symsonte\Call\ParameterConverter;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_converter']
 * })
 */
class SessionParameterConverter implements ParameterConverter
{
    /**
     * @var ConvertionStorer
     */
    private $convertionStorer;

    /**
     * @var Http\Server
     */
    private $server;

    /**
     * @param ConvertionStorer $convertionStorer
     * @param Http\Server           $server
     */
    public function __construct(
        ConvertionStorer $convertionStorer,
        Http\Server $server
    ) {
        $this->convertionStorer = $convertionStorer;
        $this->server = $server;
    }

    /**
     * {@inheritDoc}
     */
    public function convert(
        string $class,
        string $method,
        array $parameters
    ) {
        $convertions = [];
        
        $key = $this->convertionStorer->find(
            $class,
            $method,
            "http\\request\\session"
        );

        if (!$key) {
            return $convertions;
        }

        $headers = $this->server->resolveHeaders();

        if (!isset($headers['session'])) {
            return $parameters;
        }

        $convertions[$key] = $headers['session'];

        return $convertions;
    }
}
