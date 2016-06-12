<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CachedInstantiator implements Instantiator
{
    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @var array
     */
    private $instances;

    /**
     * @param Instantiator $instantiator
     */
    function __construct(
        Instantiator $instantiator
    )
    {
        $this->instantiator = $instantiator;
    }

    /**
     * {@inheritdoc}
     */
    public function support($declaration)
    {
        return $this->instantiator->support($declaration);
    }

    /**
     * @param ConstructorDeclaration $declaration
     *
     * @return object
     *
     * @throws UnsupportedDeclarationException if the declaration is not
     *                                         supported
     */
    public function instantiate($declaration)
    {
        if (!$this->support($declaration)) {
            throw new UnsupportedDeclarationException($declaration);
        }

        $hash = md5(serialize($declaration));

        if (isset($this->instances[$hash])) {
            return $this->instances[$hash];
        }

        $instance = $this->instantiator->instantiate($declaration);

        $this->instances[$hash] = $instance;

        return $instance;
    }
}