<?php

namespace Symsonte\ServiceKit\Resource\Cacher;

use Symsonte\Resource\Cacher\Approver;
use Symsonte\Resource\DelegatorNormalizer;
use Symsonte\Resource\DelegatorSliceReader;
use Symsonte\Resource\FileResource;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\SliceReader;
use Symsonte\Resource\Storer;
use Symsonte\Resource\UnsupportedResourceException;
use Symsonte\ServiceKit\Resource\ImportsNormalization;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.cacher.approver']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.cacher.approver']
 * })
 */
class FileModificationTimeApprover implements Approver
{
    /**
     * @var Storer
     */
    private $storer;

    /**
     * @var DelegatorSliceReader
     */
    private $fileSliceReader;

    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @param Storer        $storer
     * @param SliceReader[] $fileSliceReaders
     * @param Normalizer[]  $normalizers
     *
     * @ds\arguments({
     *     storer:           '@symsonte.resource.filesystem_storer',
     *     fileSliceReaders: '#symsonte.resource.file_slice_reader',
     *     normalizers:      '#symsonte.service_kit.resource.normalizer'
     * })
     *
     * @di\arguments({
     *     storer:           '@symsonte.resource.filesystem_storer',
     *     fileSliceReaders: '#symsonte.resource.file_slice_reader',
     *     normalizers:      '#symsonte.service_kit.resource.normalizer'
     * })
     */
    public function __construct(
        Storer $storer,
        array $fileSliceReaders,
        array $normalizers
    ) {
        $this->storer = $storer;
        $this->fileSliceReader = new DelegatorSliceReader($fileSliceReaders);
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function add($resource)
    {
        if (!$resource instanceof FileResource) {
            throw new UnsupportedResourceException($resource);
        }

        $this->storer->add(
            $this->calculateVersion($resource),
            $resource
        );
    }

    /**
     * {@inheritdoc}
     */
    public function approve($resource)
    {
        if (!$resource instanceof FileResource) {
            throw new UnsupportedResourceException($resource);
        }

        return $this->storer->has($resource)
               && $this->storer->get($resource) == $this->calculateVersion($resource);
    }

    /**
     * @param FileResource $resource
     *
     * @return array
     */
    private function calculateVersion(FileResource $resource)
    {
        $iterator = $this->fileSliceReader->init($resource);

        $times = [
            md5($resource->getFile()) => filemtime($resource->getFile()),
        ];
        while ($data = $this->fileSliceReader->current($iterator)) {
            $definition = $this->normalizer->normalize($data, $resource);

            if ($definition instanceof ImportsNormalization) {
                $times = array_merge(
                    $times,
                    $this->calculateVersion($definition->resource)
                );
            }

            $this->fileSliceReader->next($iterator);
        }

        return serialize($times);
    }
}
