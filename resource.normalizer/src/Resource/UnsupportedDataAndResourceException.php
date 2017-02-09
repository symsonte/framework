<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedDataAndResourceException extends \Exception
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param mixed $data
     * @param mixed $resource
     */
    public function __construct($data, $resource)
    {
        $this->data = $data;
        $this->resource = $resource;

        parent::__construct(sprintf(
            'Data %s from resource %s is unsupported.',
            serialize($data),
            serialize($resource)
        ));
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
