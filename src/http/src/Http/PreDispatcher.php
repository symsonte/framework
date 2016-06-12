<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface PreDispatcher
{
    /**
     * @param string $controller
     *
     * @return OrdinaryResponse|null
     */
    public function dispatch(
        string $controller
    );
}
