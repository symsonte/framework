<?php

namespace Symsonte;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Authorization
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string[]
     */
    private $roles;

    /**
     * @param string   $key
     * @param string[] $roles
     */
    public function __construct($key, $roles)
    {
        $this->key = $key;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
