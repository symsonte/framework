<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorTransformer implements Transformer
{
    /**
     * @var \Symsonte\Resource\Transformer[]
     */
    private $transformers;

    /**
     * @param Transformer[] $transformers
     */
    public function __construct($transformers)
    {
        // Check type
        array_map(
            function($transformer) {
                if (!$transformer instanceof Transformer) {
                    throw new \InvalidArgumentException('All transformers must implement "Symsonte\Resource\Iterator\Transformer".');
                }
            },
            $transformers
        );

        $this->transformers = $transformers;
    }

    public function support($resource, $parentResource = null)
    {
        if ($this->pick($resource, $parentResource)) {
            return true;
        }

        return false;
    }

    public function transform($resource, $parentResource = null)
    {
        $transformer = $this->pick($resource, $parentResource);
        if (!$transformer) {
            throw new \RuntimeException('No transformer is able to work with the metadata.');
        }

        return $transformer->transform($resource, $parentResource);
    }

    /**
     * @return \Symsonte\Resource\Transformer[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    private function pick($resource, $parentResource = null)
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->support($resource, $parentResource)) {
                return $this->transformers[0];
            }
        }

        return false;
    }
}
