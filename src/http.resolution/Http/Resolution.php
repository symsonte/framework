<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Resolution
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $matches;

    /**
     * @param string $key
     * @param mixed  $matches
     */
    public function __construct($key, $matches)
    {
        $this->key = $key;
        $this->matches = $matches;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getMatches()
    {
        return $this->matches;
    }
}
