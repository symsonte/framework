<?php

namespace Symsonte\Service;

use Symsonte\Service\Declaration\IdStorer;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\Service\Declaration\Storer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeductibleContainer implements Container
{
    /**
     * @var IdStorer
     */
    private $storer;

    /**
     * @var Storer
     */
    private $declarationStorer;
    
    /**
     * @var Container
     */
    private $container;

    /**
     * @param IdStorer  $storer
     * @param Storer    $declarationStorer
     * @param Container $container
     */
    function __construct(
        IdStorer $storer,
        Storer $declarationStorer,
        Container $container
    )
    {
        $this->storer = $storer;
        $this->declarationStorer = $declarationStorer;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->declarationStorer->has($id)) {
            throw new NonexistentServiceException($id);
        }
        
        if ($this->storer->has($id)) {
            $declaration = $this->declarationStorer->get($id);

            if ($declaration instanceof ConstructorDeclaration) {
                $declaration = $this->completeDeclaration($declaration);
                $this->declarationStorer->add(
                    $declaration
                );
            }
        }

        return $this->container->get($id);
    }
    
    private function completeDeclaration(ConstructorDeclaration $declaration)
    {
        $refClass = new \ReflectionClass($declaration->getClass());
        $constructor = $refClass->getConstructor();
        if (!is_null($constructor) && $constructor->getNumberOfParameters() > count($declaration->getArguments())) {
            $arguments = [];
            foreach ($constructor->getParameters() as $key => $parameter) {
                if ($declaration->hasArgument($parameter->getName())) {
                    $arguments[$parameter->getName()] = $declaration->getArgument($parameter->getName());
                } else {
                    $class = $parameter->getClass()->getName();
                    $arguments[$parameter->getName()] = new ServiceArgument($this->generateName($class));
                }
            }

            $declaration = new ConstructorDeclaration(
                $declaration->getId(),
                $declaration->getClass(),
                $arguments,
                $declaration->getCalls()
            );
        }

        return $declaration;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function generateName($class)
    {
        return
            strtolower(
                strtr(
                    preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class),
                    '\\',
                    '.'
                )
            );
    }
}