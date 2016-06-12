<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class InvalidResourceException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param mixed $resource
     *
     * {@inheritdoc}
     */
    public function __construct($resource, $message = null, $code = null, $previous = null)
    {
        $this->resource = $resource;

        parent::__construct(
            sprintf("%s\r\n%s", print_r($resource, true), $message),
            $code,
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
