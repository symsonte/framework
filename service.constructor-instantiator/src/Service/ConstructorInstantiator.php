<?php

namespace Symsonte\Service;

use Symsonte\ConstructorInstantiator as BaseConstructorInstantiator;
use Symsonte\Service\Declaration\Argument\Processor as ArgumentProcessor;
use Symsonte\Service\Declaration\Call\Processor as CallProcessor;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ConstructorInstantiator implements Instantiator
{
    /**
     * @var ArgumentProcessor
     */
    private $argumentProcessor;

    /**
     * @var CallProcessor
     */
    private $callProcessor;

    /**
     * @var BaseConstructorInstantiator
     */
    private $instantiator;

    /**
     * @param ArgumentProcessor           $argumentProcessor
     * @param CallProcessor               $callProcessor
     * @param BaseConstructorInstantiator $instantiator
     */
    public function __construct(
        ArgumentProcessor $argumentProcessor,
        CallProcessor $callProcessor,
        BaseConstructorInstantiator $instantiator
    ) {
        $this->argumentProcessor = $argumentProcessor;
        $this->callProcessor = $callProcessor;
        $this->instantiator = $instantiator;
    }

    /**
     * {@inheritdoc}
     */
    public function support($declaration)
    {
        return $declaration instanceof ConstructorDeclaration;
    }

    /**
     * Instantiates given declaration.
     *
     * @param ConstructorDeclaration $declaration
     *
     * @throws UnsupportedDeclarationException if the declaration is not supported
     *
     * @return object
     */
    public function instantiate($declaration)
    {
        if (!$this->support($declaration)) {
            throw new UnsupportedDeclarationException($declaration);
        }

        $arguments = [];
        foreach ($declaration->getArguments() as $argument) {
            $arguments[] = $this->argumentProcessor->process($argument);
        }

        $instance = $this->instantiator->instantiate($declaration->getClass(), $arguments);

        $this->callProcessor->process($instance, $declaration->getCalls());

        return $instance;
    }
}
