<?php

namespace Symsonte\Service\Declaration\Call;

use Symsonte\Service\Declaration\Call;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Storer
{
    /**
     * @var Call[][]
     */
    private $calls = [];

    /**
     * @param Call[][] $calls
     */
    function __construct($calls = [])
    {
        $this->calls = $calls;
    }

    /**
     * Adds given call to service with given id.
     *
     * @param string $id
     * @param Call   $call
     */
    public function add($id, Call $call)
    {
        $this->calls[$id][$call->getMethod()] = $call;
    }

    /**
     * Removes the calls for service with given id.
     *
     * @param $id
     */
    public function remove($id)
    {
        if (!isset($this->calls[$id])) {
            // TODO: Throws exception
            throw new \InvalidArgumentException();
        }

        unset($this->calls[$id]);
    }

    /**
     * Get call from service from given id to given method.
     *
     * @param string $id
     * @param string $method
     *
     * @return Call
     *
     * @throws \Exception
     */
    public function get($id, $method)
    {
        if (!isset($this->calls[$id], $this->calls[$id][$method])) {
            throw new \Exception();
        }

        return $this->calls[$id][$method];
    }

    /**
     * Gets all calls.
     *
     * @return Call[][]
     */
    public function all()
    {
        return $this->calls;
    }
}