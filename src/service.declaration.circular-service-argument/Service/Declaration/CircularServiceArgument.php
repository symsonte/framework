<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CircularServiceArgument implements Argument
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Gets id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
