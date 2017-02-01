<?php

namespace Symsonte\Service;

use ProxyManager\Configuration as ProxyManagerConfiguration;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use ProxyManager\Proxy\VirtualProxyInterface;
use Symsonte\Service\Declaration\Storer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class LazyInstantiator implements Instantiator
{
    /**
     * @var Storer
     */
    private $storer;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @param Storer       $storer
     * @param Instantiator $instantiator
     */
    function __construct(
        Storer $storer,
        Instantiator $instantiator
    )
    {
        $this->storer = $storer;
        $this->instantiator = $instantiator;
    }

    /**
     * {@inheritdoc}
     */
    public function support($declaration)
    {
        return $declaration instanceof ConstructorDeclaration
            && $this->instantiator->support($declaration);
    }

    /**
     * @param ConstructorDeclaration $declaration
     *
     * @return VirtualProxyInterface
     *
     * @throws UnsupportedDeclarationException if the declaration is not supported
     */
    public function instantiate($declaration)
    {
        if (!$this->support($declaration)) {
            throw new UnsupportedDeclarationException($declaration);
        }

        if ($this->storer->has($declaration)) {
            $instantiator = $this->instantiator;
            $factory = new LazyLoadingValueHolderFactory(new ProxyManagerConfiguration());

            return $factory->createProxy(
                $declaration->getClass(),
                function (&$instance, LazyLoadingInterface $proxy, $method, $parameters, &$initializer) use ($instantiator, $declaration) {
                    $instance = $instantiator->instantiate($declaration);

                    $initializer = null;

                    return true;
                }
            );
        }

        return $this->instantiator->instantiate($declaration);
    }
}
