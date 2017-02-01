<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorSliceReader implements SliceReader
{
    /**
     * @var SliceReader[]
     */
    protected $sliceReaders;

    /**
     * @param SliceReader[] $sliceReaders
     */
    function __construct($sliceReaders = [])
    {
        $this->sliceReaders = $sliceReaders;
    }

    /**
     * {@inheritdoc}
     */
    public function init($resource)
    {
        foreach ($this->sliceReaders as $sliceReader) {
            try {
                return $sliceReader->init($resource);
            } catch (UnsupportedResourceException $e) {
                continue;
            }
        }

        throw new UnsupportedResourceException($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function current($iterator)
    {
        foreach ($this->sliceReaders as $sliceReader) {
            try {
                return $sliceReader->current($iterator);
            } catch (UnsupportedSliceIteratorException $e) {
                continue;
            }
        }

        throw new UnsupportedSliceIteratorException($iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function next($iterator)
    {
        foreach ($this->sliceReaders as $sliceReader) {
            try {
                $sliceReader->next($iterator);

                return;
            } catch (UnsupportedSliceIteratorException $e) {
                continue;
            }
        }

        throw new UnsupportedSliceIteratorException($iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function close($iterator)
    {
        foreach ($this->sliceReaders as $sliceReader) {
            try {
                $sliceReader->close($iterator);

                return;
            } catch (UnsupportedSliceIteratorException $e) {
                continue;
            }
        }

        throw new UnsupportedSliceIteratorException($iterator);
    }
}