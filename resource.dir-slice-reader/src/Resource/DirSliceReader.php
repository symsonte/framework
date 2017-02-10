<?php

namespace Symsonte\Resource;

use Symfony\Component\Finder\Finder;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class DirSliceReader implements SliceReader
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @param Builder[] $builders
     *
     * @ds\arguments({
     *     builders: '#symsonte.resource.builder'
     * })
     *
     * @di\arguments({
     *     builders: '#symsonte.resource.builder'
     * })
     */
    public function __construct(array $builders)
    {
        $this->builder = new DelegatorBuilder($builders);
    }

    /**
     * {@inheritdoc}
     *
     * @return DirSliceIterator
     */
    public function init($resource)
    {
        if (!$resource instanceof DirResource) {
            throw new UnsupportedResourceException($resource);
        }

        if (!is_dir($resource->getDir())) {
            throw new InvalidResourceException(sprintf('Directory "%s" not found.', $resource->getDir()));
        }

        return $this->createIterator($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function current($iterator)
    {
        if (!$iterator instanceof DirSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        /** @var \SplFileInfo $file */
        $file = $iterator->getIterator()->current();
        if (is_null($file)) {
            return false;
        }

        $metadata = array_merge(
            [
                'file' => $file->getRealPath()
            ],
            $iterator->getResource()->getExtra()
        );

        return $this->builder->build($metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function next($iterator)
    {
        if (!$iterator instanceof DirSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $iterator->getIterator()->next();
    }

    /**
     * {@inheritdoc}
     */
    public function close($iterator)
    {
        if (!$iterator instanceof DirSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }
    }

    /**
     * @param DirResource $resource
     *
     * @return DirSliceIterator
     */
    private function createIterator(DirResource $resource)
    {
        $finder = Finder::create();
        $finder->files()->in($resource->getDir());
        if ($resource->getFilter()) {
            $finder->name($resource->getFilter());
        }
        if ($resource->getDepth()) {
            $finder->depth($resource->getDepth());
        } else {
            $finder->depth('>= 0');
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();

        return new DirSliceIterator(
            $iterator,
            $resource
        );
    }
}

