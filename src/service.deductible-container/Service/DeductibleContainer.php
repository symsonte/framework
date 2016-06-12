<?php

namespace Symsonte\Service;

use Symsonte\Service\Declaration\IdStorer;
use Symsonte\Service\Declaration\ScalarArgument;
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
    public function __construct(
        IdStorer $storer,
        Storer $declarationStorer,
        Container $container
    ) {
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
                    try {
                        $class = $parameter->getType();
                    } catch (\ReflectionException $e) {
                        throw new \Exception(sprintf(
                            "%s\n\n%s",
                            $e->getMessage(),
                            sprintf("Check parameter %s in %s", $parameter->getName(), $declaration->getClass())
                        ));
                    }

                    if (!$class) {
                        try {
                            $value = $parameter->getDefaultValue();
                        } catch (\ReflectionException $e) {
                            throw new \Exception(sprintf(
                                "%s\n\n%s",
                                $e->getMessage(),
                                sprintf("Check parameter %s in %s", $parameter->getName(), $declaration->getClass())
                            ));
                        }
                        $arguments[$parameter->getName()] = new ScalarArgument($value);

                        continue;
                    }

                    $arguments[$parameter->getName()] = new ServiceArgument(
                        $this->generateName($class),
                        $parameter->allowsNull()
                    );
                }
            }

            $declaration = new ConstructorDeclaration(
                $declaration->getId(),
                $declaration->getClass(),
                $declaration->isLazy(),
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
        $class = preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class);
        // TODO: Remove ? (optional parameter)
        $class = str_replace('?', '', $class);

        return strtolower(
            strtr(
                $class,
                '\\',
                '.'
            )
        );
    }
}
