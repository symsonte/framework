<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Cacher
{
    /**
     * Caches given data from given resource.
     *
     * @param mixed $resource
     * @param mixed $data
     *
     * @return mixed
     */
    public function store($data, $resource);

    /**
     * Returns whether cacher has cached data for given resource.
     *
     * @param mixed $resource
     *
     * @return mixed true if the cacher has the cached data for given resource,
     *               false otherwise
     */
    public function approve($resource);

    /**
     * Gets cached data from given resource.
     *
     * @param mixed $resource
     *
     * @return mixed
     */
    public function retrieve($resource);
}
