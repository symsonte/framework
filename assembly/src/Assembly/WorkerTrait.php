<?php

namespace Symsonte\Assembly;

trait WorkerTrait
{
    /**
     * @var Worker
     */
    private $worker;

    /**
     * @param Worker $worker
     */
    public function setNext(Worker $worker)
    {
        $this->worker = $worker;
    }
}