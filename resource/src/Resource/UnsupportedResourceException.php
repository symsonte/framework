<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedResourceException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param mixed $resource
     */
    function __construct($resource)
    {
        $this->resource = $resource;

        parent::__construct(sprintf("Resource %s is not supported.", serialize($resource)));
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}