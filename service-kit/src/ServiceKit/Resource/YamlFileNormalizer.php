<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\DelegatorNormalizer;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\YamlFileResource;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.file_normalizer', 'symsonte.service_kit.resource.normalizer']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.file_normalizer', 'symsonte.service_kit.resource.normalizer']
 * })
 */
class YamlFileNormalizer implements Normalizer
{
    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @param Normalizer[] $normalizers
     *
     * @ds\arguments({
     *     normalizers: '#symsonte.service_kit.resource.yaml_file_normalizer'
     * })
     *
     * @di\arguments({
     *     normalizers: '#symsonte.service_kit.resource.yaml_file_normalizer'
     * })
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
        if (!$resource instanceof YamlFileResource) {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        return $this->normalizer->normalize($data, $resource);
    }
}