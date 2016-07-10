<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ServiceArgument implements Argument
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var bool
     */
    private $optional;

    /**
     * @param string $id
     * @param bool   $optional
     */
    public function __construct($id, $optional = false)
    {
        $this->id = $id;
        $this->optional = $optional;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return $this->optional;
    }
}
