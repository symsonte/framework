<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorNormalizer implements Normalizer
{
    /**
     * @var Normalizer[]
     */
    protected $normalizers;

    /**
     * @param Normalizer[] $normalizers
     */
    function __construct($normalizers = [])
    {
        $this->normalizers = $normalizers;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $resource)
    {
        foreach ($this->normalizers as $normalizer) {
            try {
                return $normalizer->normalize($data, $resource);
            } catch (UnsupportedDataAndResourceException $e) {
                continue;
            }
        }

        throw new UnsupportedDataAndResourceException($data, $resource);
    }
}