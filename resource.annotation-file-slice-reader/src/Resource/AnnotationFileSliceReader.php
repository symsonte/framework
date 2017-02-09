<?php

namespace Symsonte\Resource;

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
class AnnotationFileSliceReader implements SliceReader
{
    /**
     * @var DataSliceReader
     */
    private $reader;

    /**
     * @var DocParser
     */
    protected $parser;

    /**
     * @param DataSliceReader $reader
     * @param DocParser       $parser
     *
     * @ds\arguments({
     *     reader: "@symsonte.resource.data_slice_reader",
     *     parser: "@symsonte.resource.yaml_doc_parser"
     * })
     *
     * @di\arguments({
     *     reader: "@symsonte.resource.data_slice_reader",
     *     parser: "@symsonte.resource.yaml_doc_parser"
     * })
     */
    public function __construct(
        DataSliceReader $reader,
        DocParser $parser
    ) {
        $this->reader = $reader;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function init($resource)
    {
        if (!$resource instanceof AnnotationFileResource) {
            throw new UnsupportedResourceException($resource);
        }

        if (!is_file($resource->getFile())) {
            throw new InvalidResourceException($resource, sprintf('Can not find file "%s".', $resource->getFile()));
        }

        try {
            $data = $this->getData($resource->getFile(), $resource->getAnnotation());
        } catch (\Exception $e) {
            throw new InvalidResourceException($resource, $e->getMessage(), null, $e);
        }

        $iterator = $this->reader->init(new DataResource($data));

        // TODO: Implement a lazy iterator, because this way the reader takes too much time reading annotation, just for
        // cache purposes on FileModificationTimeApprover
        return new AnnotationFileSliceIterator($iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function current($iterator)
    {
        if (!$iterator instanceof AnnotationFileSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        return $this->reader->current($iterator->getIterator());
    }

    /**
     * {@inheritdoc}
     */
    public function next($iterator)
    {
        if (!$iterator instanceof AnnotationFileSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $this->reader->next($iterator->getIterator());
    }

    /**
     * {@inheritdoc}
     */
    public function close($iterator)
    {
        if (!$iterator instanceof AnnotationFileSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $this->reader->close($iterator->getIterator());
    }

    /**
     * @param string $file
     * @param string $annotation
     *
     * @return array
     */
    private function getData($file, $annotation)
    {
        $data = $this->parser->parse($file, $annotation);

        if (!$data) {
            return [];
        }

        return [
            0 => $data,
        ];
    }
}
