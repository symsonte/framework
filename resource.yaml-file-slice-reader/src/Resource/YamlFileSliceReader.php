<?php

namespace Symsonte\Resource;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.slice_reader', 'symsonte.resource.file_slice_reader']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.slice_reader', 'symsonte.resource.file_slice_reader']
 * })
 */
class YamlFileSliceReader implements SliceReader
{
    /**
     * @var DataSliceReader
     */
    private $reader;

    /**
     * @param DataSliceReader $reader
     *
     * @ds\arguments({
     *     reader: '@symsonte.resource.data_slice_reader'
     * })
     *
     * @di\arguments({
     *     reader: '@symsonte.resource.data_slice_reader'
     * })
     */
    public function __construct(DataSliceReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function init($resource)
    {
        if (!$resource instanceof YamlFileResource) {
            throw new UnsupportedResourceException($resource);
        }

        if (!is_file($resource->getFile())) {
            throw new InvalidResourceException($resource, sprintf('Can not find file "%s".', $resource->getFile()));
        }

        try {
            $data = (array) Yaml::parse(file_get_contents($resource->getFile()));
        } catch (\Exception $e) {
            throw new InvalidResourceException($resource, $e->getMessage(), 0, $e);
        }

        $iterator = $this->reader->init(new DataResource($data));

        return new YamlFileSliceIterator($iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function current($iterator)
    {
        if (!$iterator instanceof YamlFileSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        return $this->reader->current($iterator->getIterator());
    }

    /**
     * {@inheritdoc}
     */
    public function next($iterator)
    {
        if (!$iterator instanceof YamlFileSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $this->reader->next($iterator->getIterator());
    }

    /**
     * {@inheritdoc}
     */
    public function close($iterator)
    {
        if (!$iterator instanceof YamlFileSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $this->reader->close($iterator->getIterator());
    }
}
