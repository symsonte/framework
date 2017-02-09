<?php

namespace Symsonte\Event;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Notifier
{
    /**
     * @param mixed $listener
     */
    public function subscribe($listener);

    public function start();

    public function stop();
}
