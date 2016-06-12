<?php

namespace Symsonte\ServiceKit\Declaration;

use Symsonte\ServiceKit\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Bag
{
    /**
     * @var Declaration[]
     */
    private $declarations;

    /**
     * @var string[]
     */
    private $parameters;

    /**
     * @var object[]
     */
    private $objects;

    /**
     * @param Declaration[]|null $declarations
     * @param string[]|null      $parameters
     * @param object[]|null      $objects
     */
    public function __construct(
        array $declarations = null,
        array $parameters = null,
        array $objects = null
    ) {
        $this->declarations = $declarations ?: [];
        $this->parameters = $parameters ?: [];
        $this->objects = $objects ?: [];
    }

    /**
     * @return Declaration[]
     */
    public function getDeclarations()
    {
        return $this->declarations;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasDeclaration($id)
    {
        return isset($this->declarations[$id]);
    }

    /**
     * @param string $id
     *
     * @throws \Exception
     *
     * @return Declaration
     */
    public function getDeclaration($id)
    {
        if (!$this->hasDeclaration($id)) {
            throw new \Exception();
        }

        return $this->declarations[$id];
    }

    /**
     * @return string[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return object[]
     */
    public function getObjects()
    {
        return $this->objects;
    }
}
