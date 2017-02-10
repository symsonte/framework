<?php

namespace Symsonte\Service\Declaration;

use Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Storer
{
    /**
     * @var Declaration[]
     */
    private $declarations;

    /**
     * @param Declaration[] $declarations
     */
    function __construct($declarations = [])
    {
        $this->declarations = $declarations;
    }

    /**
     * Adds a declaration.
     *
     * @param Declaration $declaration
     */
    public function add(Declaration $declaration)
    {
        $this->declarations[$declaration->getId()] = $declaration;
    }

    /**
     * Returns whether declaration with given id exists.
     *
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->declarations[$id]);
    }

    /**
     * Get declaration with given id.
     *
     * @param $id
     *
     * @return Declaration
     *
     * @throws NonexistentDeclarationException if declaration does not exist
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NonexistentDeclarationException($id);
        }

        return $this->declarations[$id];
    }

    /**
     * Gets all declaration.
     *
     * @return Declaration[]
     */
    public function all()
    {
        return $this->declarations;
    }

    /**
     * @param Declaration[] $declarations
     */
    public function merge($declarations)
    {
        $this->declarations = array_merge(
            $this->declarations,
            $declarations
        );
    }
}