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
     * @return mixed The data.
     *
     * @throws UnsupportedResourceException If given resource is not supported
     * @throws InvalidResourceException     If given resource is invalid
     */
    public function read($resource);
}
