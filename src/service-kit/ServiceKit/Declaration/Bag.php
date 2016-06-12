<?php

namespace Symsonte\ServiceKit\Declaration;

use Symsonte\ServiceKit\Declaration;
use Symsonte\ServiceKit\Instance;

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
     * @var Instance[]
     */
    private $instances;

    /**
     * @param Declaration[]|null $declarations
     * @param string[]|null      $parameters
     * @param Instance[]|null    $instances
     */
    public function __construct(
        array $declarations = null,
        array $parameters = null,
        array $instances = null
    ) {
        $this->declarations = $declarations ?: [];
        $this->parameters = $parameters ?: [];
        $this->instances = $instances ?: [];
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
     * @return Instance[]
     */
    public function getInstances()
    {
        return $this->instances;
    }
}
