<?php

namespace Symsonte\Http;

use Symsonte\Http\Server\ControllerDispatcher;
use Symsonte\Service\Container;

class App
{
    /**
     * @var Container
     */
    private $serviceContainer;

    /**
     * @param Container $serviceContainer
     */
    public function __construct(Container $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function execute()
    {
        /** @var ControllerDispatcher $dispatcher */
        $dispatcher = $this->serviceContainer->get('airsol.http.server.controller_dispatcher');
        $dispatcher->dispatch();
    }
}