<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class AliasStorer
{
    /**
     * @var string[]
     */
    private $aliases;

    /**
     * @param string[] $aliases
     */
    function __construct($aliases = [])
    {
        $this->aliases = $aliases;
    }

    /**
     * @param $alias
     * @param $id
     */
    public function add($alias, $id)
    {
        $this->aliases[$alias] = $id;
    }

    /**
     * Returns whether alias with given id exists.
     *
     * @param $alias
     *
     * @return bool
     */
    public function has($alias)
    {
        return isset($this->aliases[$alias]);
    }

    /**
     * @param $alias
     *
     * @return string|false
     */
    public function get($alias)
    {
        if (!$this->has($alias)) {
            return false;
        }

        return $this->aliases[$alias];
    }

    /**
     * Gets all alias.
     *
     * @return string[]
     */
    public function all()
    {
        return $this->aliases;
    }

    /**
     * @param string[] $aliases
     */
    public function merge($aliases)
    {
        $this->aliases = array_merge(
            $this->aliases,
            $aliases
        );
    }
}