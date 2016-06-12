<?php

namespace Symsonte\Cli\Server;

use Symsonte\Caller;
use Symsonte\Cli\Server;
use Symsonte\Cli\Server\Input\Resolution\Finder;
use Symsonte\Resource\Builder;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Service\Container;
use Symsonte\ServiceKit\Resource\CachedLoader;
use ReflectionMethod;
use ReflectionException;
use LogicException;

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
class CommandDispatcher
{
    /**
     * @var Container
     */
    private $serviceContainer;

    /**
     * @var Finder
     */
    private $commandFinder;

    /**
     * @var Caller
     */
    private $commandCaller;

    /**
     * @var Server
     */
    private $server;

    /**
     * @param Container $serviceContainer
     * @param Finder    $commandFinder
     * @param Caller    $commandCaller
     * @param Server    $server
     */
    public function __construct(
        Container $serviceContainer,
        Finder $commandFinder,
        Caller $commandCaller,
        Server $server
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->commandFinder = $commandFinder;
        $this->commandCaller = $commandCaller;
        $this->server = $server;
    }

    /**
     */
    public function dispatch()
    {
        $input = $this->server->resolveInput();

        $command = $this->commandFinder->first($input);

        $i = 2;
        $variables = [];
        while ($this->server->resolveInput()->get($i) !== null) {
            $variables[] = $this->server->resolveInput()->get($i);
            $i++;
        }

        list($command, $method) = explode(':', $command);
        $command = $this->serviceContainer->get($command);
        try {
            $reflectionMethod = new ReflectionMethod($command, $method);
        } catch (ReflectionException $e) {
            throw new LogicException(null, null, $e);
        }
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $i => $parameter) {
            $parameters[$i] = $variables[$i];
        }

        $response = $this->commandCaller->call($command, $method, $parameters);

        $this->server->resolveOutput()->outln(print_r($response, true));
    }
}
