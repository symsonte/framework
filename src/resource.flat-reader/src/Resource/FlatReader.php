<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface FlatReader
{
    /**
     * @param mixed $resource
     *
     * @throws UnsupportedResourceException If given resource is not supported
     * @throws InvalidResourceException     If given resource is invalid
     *
     * @return mixed The data.
     */
    public function read($resource);
}
