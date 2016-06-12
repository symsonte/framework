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
     * @param mixed $metadata
     *
     * @throws UnsupportedMetadataException If the metadata is not supported.
     *
     * @return mixed The already built resource.
     */
    public function build($metadata);
}
