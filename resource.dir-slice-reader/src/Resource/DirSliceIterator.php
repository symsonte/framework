<?php

namespace Symsonte\Resource;

class DirSliceIterator
{
    /**
     * @var \Iterator
     */
    private $iterator;

    /**
     * @var DirResource
     */
    private $resource;

    /**
     * @param \Iterator   $iterator
     * @param DirResource $resource
     */
    public function __construct(
        \Iterator $iterator,
        DirResource $resource
    ) {
        $this->iterator = $iterator;
        $this->resource = $resource;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * @return DirResource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
