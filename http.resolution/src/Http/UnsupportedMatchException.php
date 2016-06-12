<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedMatchException extends \Exception
{
    /**
     * @var mixed
     */
    private $match;

    /**
     * @param mixed $match
     */
    public function __construct($match)
    {
        $this->match = $match;

        parent::__construct(sprintf(
            'Match %s unsupported.',
            serialize($match)
        ));
    }

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }
}
