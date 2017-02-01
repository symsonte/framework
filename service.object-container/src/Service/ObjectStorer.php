<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ObjectStorer
{
    /**
     * @var object[]
     */
    private $objects;

    /**
     * @param object[] $objects
     */
    function __construct($objects = [])
    {
        $this->objects = $objects;
    }

    /**
     * @param string $id
     * @param object $object
     */
    public function add($id, $object)
    {
        $this->objects[$id] = $object;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->objects[$id]);
    }

    /**
     * @param $id
     *
     * @return object
     *
     * @throws
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            // TODO: Throw custom exception

            throw new \Exception();
        }

        return $this->objects[$id];
    }

    /**
     * @return object[]
     */
    public function all()
    {
        return $this->objects;
    }
}