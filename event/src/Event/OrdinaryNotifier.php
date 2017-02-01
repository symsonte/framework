<?php

namespace Symsonte\Event;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class OrdinaryNotifier
{
    /**
     * @var array
     */
    private $listeners;

    /**
     * @param array $listeners
     */
    public function __construct(array $listeners = [])
    {
        $this->listeners = $listeners;
    }

    /**
     * @param callable $listener
     */
    public function subscribe($listener)
    {
        $this->listeners[] = $listener;
    }

    public function start()
    {
        foreach ($this->listeners as $listener) {
            call_user_func($listener, func_get_args());
        }
    }
}
