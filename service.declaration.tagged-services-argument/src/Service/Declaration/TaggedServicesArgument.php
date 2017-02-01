<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class TaggedServicesArgument implements Argument
{
    /**
     * @var string
     */
    private $tag;

    /**
     * @param string $tag
     */
    function __construct($tag)
    {
        $this->tag = $tag;
    }

    /**
     * Gets tag.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
}