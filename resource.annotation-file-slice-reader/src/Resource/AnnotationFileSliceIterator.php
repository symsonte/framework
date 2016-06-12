<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class AnnotationFileSliceIterator implements \Iterator
{
    /**
     * @var DataSliceIterator
     */
    private $iterator;

    /**
     * @param DataSliceIterator $iterator
     */
    public function __construct(DataSliceIterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @return DataSliceIterator
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }
}
