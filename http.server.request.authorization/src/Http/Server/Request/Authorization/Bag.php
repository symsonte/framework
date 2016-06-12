<?php

namespace Symsonte\Http\Server\Request\Authorization;

use Symsonte\Http\Server\Request\Authorization;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Bag
{
    /**
     * @var Authorization[]
     */
    private $authorizations;

    /**
     * @param Authorization[]|null $authorizations
     */
    function __construct($authorizations = [])
    {
        $this->authorizations = $authorizations;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->authorizations[$key]);
    }

    /**
     * @param string $key
     *
     * @return Authorization
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException();
        }

        return $this->authorizations[$key];
    }

    /**
     * @param Authorization $authorization
     */
    public function add(Authorization $authorization)
    {
        $this->authorizations[$authorization->getKey()] = $authorization;
    }

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag)
    {
        $this->authorizations = array_merge(
            $this->authorizations,
            $bag->all()
        );
    }

    /**
     * @return Authorization[]
     */
    public function all()
    {
        return $this->authorizations;
    }
}