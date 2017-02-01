<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Container
{
    /**
     * Returns whether service with given id exists.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id);

    /**
     * Gets service with given id.
     *
     * @param string $id
     *
     * @return object
     *
     * @throws NonexistentServiceException     if the service does not exist
     * @throws NonInstantiableServiceException if the service can't be instantiable
     */
    public function get($id);
}