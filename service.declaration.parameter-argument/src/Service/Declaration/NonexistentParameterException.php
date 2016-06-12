<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonexistentParameterException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    function __construct($key)
    {
        $this->key = $key;

        parent::__construct(sprintf("Parameter with key \"%s\" does not exist.", $key));
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}