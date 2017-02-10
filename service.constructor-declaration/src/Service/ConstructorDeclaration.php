<?php

namespace Symsonte\Service;

use Symsonte\Service\Declaration\Argument;
use Symsonte\Service\Declaration\Call;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ConstructorDeclaration implements Declaration
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    /**
     * @var Argument[]
     */
    private $arguments;

    /**
     * @var Call[]
     */
    private $calls;

    /**
     * @param string|null     $id
     * @param string          $class
     * @param Argument[]|null $arguments
     * @param Call[]|null     $calls
     */
    function __construct($id = null, $class, $arguments = null, $calls = null)
    {
        $this->id = $id;
        $this->class = $class;
        $this->arguments = $arguments ?: [];
        $this->calls = $calls ?: [];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Argument[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasArgument($key)
    {
        return isset($this->arguments[$key]);
    }

    /**
     * @param string $key
     *
     * @return Argument
     */
    public function getArgument($key)
    {
        return $this->arguments[$key];
    }

    /**
     * @return Call[]
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @param Call $call
     */
    public function addCall(Call $call)
    {
        $this->calls[]  = $call;
    }
}