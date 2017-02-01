<?php

namespace Symsonte\Resource\Builder;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class InvalidMetadataException extends \InvalidArgumentException
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * @param array $metadata
     */
    function __construct($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}