<?php

namespace Symsonte\Http\Server\Request;

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
     * @var array
     */
    private $roles;

    /**
     * @param string $key
     * @param array  $roles
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
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}