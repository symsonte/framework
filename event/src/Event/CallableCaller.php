<?php

namespace Symsonte\Event;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CallableCaller implements Caller
{
    /**
     * @inheritdoc
     */
    public function support($listener)
    {
        return is_callable($listener);
    }

    /**
     * Calls given listener passing given involved.
     *
     * @param mixed $listener
     * @param mixed $involved
     *
     * @throws \InvalidArgumentException if listener is not supported
     */
    public function call($listener, $involved)
    {
        if (!$this->support($listener)) {
            throw new \InvalidArgumentException();
        }

        call_user_func($listener, $involved);
    }
}