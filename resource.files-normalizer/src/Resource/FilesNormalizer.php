<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class FilesNormalizer implements Normalizer
{
    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @param Normalizer[] $normalizers
     */
    function __construct(array $normalizers)
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $resource)
    {
        if (!$resource instanceof DirResource) {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        return $this->normalizer->normalize($data, $data['_resource']);
    }
}