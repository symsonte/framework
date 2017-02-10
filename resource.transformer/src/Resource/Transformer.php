<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Transformer
{
    /**
     * Returns whether transformer supports the given resource and parent
     * resource.
     *
     * @param  mixed   $resource
     * @param  mixed   $parentResource
     * @return boolean true if the transformer supports the resource and parent
     *                 resource, false otherwise
     */
    public function support($resource, $parentResource = null);

    /**
     * Transforms the resource into another resource.
     *
     * @param  mixed $resource
     * @param  mixed $parentResource
     * @return mixed The transformed resource
     */
    public function transform($resource, $parentResource = null);
}
