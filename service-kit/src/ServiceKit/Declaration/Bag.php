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
     * @param Declaration[]|null $declarations
     * @param string[]|null      $parameters
     */
    public function __construct(
        array $declarations = null,
        array $parameters = null
    ) {
        $this->declarations = $declarations ?: [];
        $this->parameters = $parameters ?: [];
    }

    /**
     * @return Declaration[]
     */
    public function getDeclarations()
    {
        return $this->declarations;
    }

    public function addDeclaration(Declaration $declaration)
    {
        $this->declarations[$declaration->getDeclaration()->getId()] = $declaration;
    }

    /**
     * @return string[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function addAliases($aliases)
    {
        foreach ($aliases as $alias => $id) {
            if (!isset($this->declarations[$id])) {
                throw new \InvalidArgumentException();
            }

            $this->declarations[$id] = new Declaration(
                $this->declarations[$id]->getDeclaration(),
                $this->declarations[$id]->isDeductible(),
                $this->declarations[$id]->isPrivate(),
                $this->declarations[$id]->isDisposable(),
                $this->declarations[$id]->getTags(),
                array_merge(
                    $this->declarations[$id]->getAliases(),
                    [$alias]
                )
            );
        }
    }

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag)
    {
        $this->declarations = array_merge(
            $this->declarations,
            $bag->getDeclarations()
        );

//        foreach ($bag->getDeclarations() as $declaration) {
//            $this->addDeclaration($declaration);
//        }
    }
}
