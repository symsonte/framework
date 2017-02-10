<?php

namespace Symsonte\Http\Server\Request\Authorization;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class Checker
{
    /**
     * @var Bag
     */
    private $bag;

    /**
     */
    function __construct()
    {
        $this->bag = new Bag();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->bag->has($key);
    }

    /**
     * @param string   $key
     * @param string[] $roles
     *
     * @return bool|string
     */
    public function check($key, $roles)
    {
        foreach ($roles as $role) {
            if (in_array($role, $this->bag->get($key)->getRoles())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag)
    {
        $this->bag->merge($bag);
    }
}