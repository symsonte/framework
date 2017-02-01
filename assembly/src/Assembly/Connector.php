<?php

namespace Symsonte\Assembly;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class Connector
{
    /**
     * @var Worker[]
     */
    private $workers;

    /**
     * @param Worker[] $workers
     *
     * @di\arguments({
     *     connector: '#symsonte.assembly.worker'
     * })
     */
    function __construct($workers)
    {
        $this->workers = $workers;
    }

    /**
     */
    public function connect()
    {
        foreach ($this->workers as $i => $worker) {
            if (isset($this->workers[$i + 1])) {
                $worker->setNext($this->workers[$i + 1]);
            }
        }
    }

    /**
     * @return Worker[]
     */
    public function getWorkers()
    {
        return $this->workers;
    }
}