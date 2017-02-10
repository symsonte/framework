<?php

namespace Symsonte\Event;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Caller
{
    /**
     * Returns whether given listener is supported.
     *
     * @param mixed $listener
     *
     * @return bool true if listener is supported, false otherwise
     */
    public function support($listener);

    /**
     * Calls given listener passing given involved.
     *
     * @param string $listener
     * @param mixed  $involved
     *
     * @throws \InvalidArgumentException if listener is not supported
     */
    public function call($listener, $involved);
}
