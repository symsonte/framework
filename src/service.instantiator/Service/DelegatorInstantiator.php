<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorInstantiator implements Instantiator
{
    /**
     * @var Instantiator[]
     */
    private $instantiators;

    /**
     * @param Instantiator[] $instantiators
     */
    public function __construct($instantiators)
    {
        $this->instantiators = $instantiators;
    }

    /**
     * {@inheritdoc}
     */
    public function support($declaration)
    {
        if ($this->pick($declaration)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function instantiate($declaration)
    {
        $instantiator = $this->pick($declaration);
        if (!$instantiator) {
            throw new UnsupportedDeclarationException($declaration);
        }

        return $instantiator->instantiate($declaration);
    }

    /**
     * Picks an instantiator that supports given declaration.
     *
     * @param Declaration $declaration
     *
     * @return Instantiator|bool
     */
    private function pick(Declaration $declaration)
    {
        foreach ($this->instantiators as $instantiator) {
            if ($instantiator->support($declaration)) {
                return $instantiator;
            }
        }

        return false;
    }
}
