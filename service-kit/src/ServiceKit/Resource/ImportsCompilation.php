<?php

namespace Symsonte\ServiceKit\Resource;

class ImportsCompilation
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * @param array $metadata
     */
    public function __construct(array $metadata)
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
