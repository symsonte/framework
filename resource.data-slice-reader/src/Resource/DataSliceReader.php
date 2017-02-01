<?php

namespace Symsonte\Resource;

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
class DataSliceReader implements SliceReader
{
    /**
     * {@inheritdoc}
     *
     * @return DataSliceIterator
     */
    public function init($resource)
    {
        if (!$resource instanceof DataResource) {
            throw new UnsupportedResourceException($resource);
        }

        return new DataSliceIterator($resource->getData());
    }

    /**
     * {@inheritdoc}
     *
     * @return array Associative array with current key and value.
     */
    public function current($iterator)
    {
        if (!$iterator instanceof DataSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        if ($iterator->current() == false) {
            return false;
        }

        return [
            'key'   => $iterator->key(),
            'value' => $iterator->current(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function next($iterator)
    {
        if (!$iterator instanceof DataSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function close($iterator)
    {
        if (!$iterator instanceof DataSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }
    }
}
