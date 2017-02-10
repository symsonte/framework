<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Normalizer
{
    /**
     * Normalizes the given data from given resource.
     *
     * @param  mixed $data
     * @param  mixed $resource
     *
     * @return mixed The normalization
     *
     * @throws UnsupportedDataAndResourceException if the given data from the
     *                                             given resource is not supported.
     */
    public function normalize($data, $resource);
}
