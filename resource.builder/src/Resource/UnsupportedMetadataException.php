<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedMetadataException extends \Exception
{
    /**
     * @var mixed
     */
    private $metadata;

    /**
     * @param mixed $metadata
     */
    public function __construct($metadata)
    {
        $this->metadata = $metadata;

        parent::__construct(sprintf(
            'Metadata %s unsupported.',
            serialize($metadata)
        ));
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
