<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Storer
{
    /**
     * Adds given data from given resource to the storer.
     *
     * @param mixed $data
     * @param mixed $resource
     */
    public function add($data, $resource);

    /**
     * Returns whether storer has data for given resource.
     *
     * @param  mixed   $resource
     *
     * @return boolean True if the storer has the data for given resource, false
     *                 otherwise
     */
    public function has($resource);

    /**
     * Gets data for given resource.
     *
     * @param  mixed $resource
     *
     * @return mixed The resource data
     */
    public function get($resource);
}
