<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedSliceIteratorException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $iterator;

    /**
     * @param mixed $iterator
     */
    function __construct($iterator)
    {
        $this->iterator = $iterator;

        parent::__construct(sprintf("Iterator %s is not supported.", serialize($iterator)));
    }

    /**
     * @return mixed
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}