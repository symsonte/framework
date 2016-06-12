<?php

namespace Symsonte\Http;

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

    /**
     * @param string $controller
     */
    public function execute($controller)
    {
        /** @var ControllerDispatcher $dispatcher */
        $dispatcher = $this->serviceContainer->get($controller);
        $dispatcher->dispatch();
    }
}
