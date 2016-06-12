<?php

namespace Symsonte\Cli\Server\Input\Resolution\Resource;

use Symsonte\Resource\FilesNormalizer as InternalFilesNormalizer;
use Symsonte\Resource\Normalizer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.resolution.resource.normalizer']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.resolution.resource.normalizer']
 * })
 */
class FilesNormalizer implements Normalizer
{
    /**
     * @var InternalFilesNormalizer
     */
    private $normalizer;

    /**
     * @param Normalizer[] $normalizers
     *
     * @ds\arguments({
     *     normalizers: '#symsonte.cli.server.input.resolution.resource.file_normalizer'
     * })
     *
     * @di\arguments({
     *     normalizers: '#symsonte.cli.server.input.resolution.resource.file_normalizer'
     * })
     */
    public function __construct(array $normalizers)
    {
        $this->normalizer = new InternalFilesNormalizer($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $resource)
    {
        return $this->normalizer->normalize($data, $resource);
    }
}
