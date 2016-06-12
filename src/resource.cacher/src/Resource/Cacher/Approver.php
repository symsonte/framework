<?php

namespace Symsonte\Resource\Cacher;

/**
 * An approver is a class used in the cache system to determine whether a
 * resource is approved.
 *
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Approver
{
    /**
     * Adds given resource into the approver.
     *
     * @param mixed $resource
     */
    public function add($resource);

    /**
     * Returns whether given resource is approved.
     *
     * @param mixed $resource
     *
     * @return bool True if the cache is still valid, false otherwise
     */
    public function approve($resource);
}
