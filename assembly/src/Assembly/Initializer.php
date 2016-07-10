<?php

namespace Symsonte\Assembly;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class Initializer
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @param Connector $connector
     *
     * @di\arguments({
     *     connector: '@symsonte.assembly.connector'
     * })
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    public function init()
    {
        $this->connector->connect();

        if (isset($this->connector->getWorkers()[0])) {
            $this->connector->getWorkers()[0]->work();
        }
    }
}
