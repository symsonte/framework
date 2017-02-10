<?php

namespace Symsonte\Http\Server\Request\Resolution;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Finder
{
    /**
     * @param string $method
     * @param string $uri
     * @param string $version
     * @param array  $headers
     * @param mixed  $body
     *
     * @return string|false The matched key or false.
     */
    public function first($method, $uri, $version, $headers, $body);

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag);
}
