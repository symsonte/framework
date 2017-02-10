<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Builder
{
    /**
     * Builds a resource using given metadata.
     *
     * @param  mixed $metadata
     *
     * @return mixed The already built resource.
     *
     * @throws UnsupportedMetadataException If the metadata is not supported.
     */
    public function build($metadata);
}
