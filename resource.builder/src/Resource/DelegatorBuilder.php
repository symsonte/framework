<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorBuilder implements Builder
{
    /**
     * @var Builder[]
     */
    protected $builders;

    /**
     * @param Builder[] $builders
     */
    function __construct($builders = [])
    {
        $this->builders = $builders;
    }

    /**
     * {@inheritdoc}
     */
    public function build($metadata)
    {
        foreach ($this->builders as $builder) {
            try {
                return $builder->build($metadata);
            } catch (UnsupportedMetadataException $e) {
                continue;
            }
        }

        throw new UnsupportedMetadataException($metadata);
    }
}
