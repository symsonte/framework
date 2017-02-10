<?php

namespace Symsonte\ServiceKit\Container;

use Symsonte\ConstructorInstantiator as BaseConstructorInstantiator;
use Symsonte\Service\AliasContainer;
use Symsonte\Service\CachedInstantiator;
use Symsonte\Service\CircularContainer;
use Symsonte\Service\ConstructorInstantiator;
use Symsonte\Service\Container as BaseContainer;
use Symsonte\Service\Declaration\AliasStorer;
use Symsonte\Service\Declaration\Argument\DelegatorProcessor;
use Symsonte\Service\Declaration\Argument\ObjectProcessor;
use Symsonte\Service\Declaration\Argument\ParameterProcessor as ParameterArgumentProcessor;
use Symsonte\Service\Declaration\Argument\ScalarProcessor as ScalarArgumentProcessor;
use Symsonte\Service\Declaration\Argument\ServiceProcessor as ServiceArgumentProcessor;
use Symsonte\Service\Declaration\Argument\TaggedServicesProcessor;
use Symsonte\Service\Declaration\Call\Processor as CallProcessor;
use Symsonte\Service\Declaration\Call\Storer as CallStorer;
use Symsonte\Service\Declaration\IdStorer;
use Symsonte\Service\Declaration\ParameterStorer;
use Symsonte\Service\Declaration\Storer;
use Symsonte\Service\Declaration\TagStorer;
use Symsonte\Service\DeductibleContainer;
use Symsonte\Service\DelegatorContainer;
use Symsonte\Service\ObjectContainer;
use Symsonte\Service\ObjectStorer;
use Symsonte\Service\OrdinaryContainer;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Builder
{
    /**
     * @param Bag $bag
     *
     * @return BaseContainer
     */
    public function build(Bag $bag)
    {
        $serviceArgumentProcessor = new ServiceArgumentProcessor();
        $taggedServicesArgumentProcessor = new TaggedServicesProcessor($this->createTagStorer($bag));
        $parameterArgumentProcessor = new ParameterArgumentProcessor(
            $this->createParameterStorer($bag)
        );
        $objectArgumentProcessor = new ObjectProcessor();
        $scalarArgumentProcessor = new ScalarArgumentProcessor();
        $argumentProcessor = new DelegatorProcessor([
            $serviceArgumentProcessor,
            $taggedServicesArgumentProcessor,
            $parameterArgumentProcessor,
            $objectArgumentProcessor,
            $scalarArgumentProcessor,
        ]);
        $callProcessor = new CallProcessor($argumentProcessor);
        $declarationStorer = $this->createDeclarationStorer($bag);
        $objectContainer = new ObjectContainer(new ObjectStorer());
        $container = new DelegatorContainer([
            new CircularContainer(
                new AliasContainer(
                    $this->createAliasStorer($bag),
                    new DeductibleContainer(
                        $this->createDeductibleStorer($bag),
                        $declarationStorer,
                        new OrdinaryContainer(
                            $declarationStorer,
                            new CachedInstantiator(
                                new ConstructorInstantiator(
                                    $argumentProcessor,
                                    $callProcessor,
                                    new BaseConstructorInstantiator()
                                )
                            )
                        )
                    )
                ),
                $this->createCircularCallStorer($bag),
                $callProcessor
            ),
            $objectContainer,
        ]);
        $objectContainer->add('symsonte.service_kit.container', $container);
        $serviceArgumentProcessor->setContainer($container);
        $taggedServicesArgumentProcessor->setContainer($container);

        return $container;
    }

    /**
     * @param Bag $bag
     *
     * @return ParameterStorer
     */
    private function createParameterStorer(Bag $bag)
    {
        $storer = new ParameterStorer();

        foreach ($bag->getParameters() as $key => $value) {
            $storer->add($key, $value);
        }

        return $storer;
    }

    /**
     * @param Bag $bag
     *
     * @return IdStorer
     */
    private function createDeductibleStorer(Bag $bag)
    {
        $storer = new IdStorer();

        foreach ($bag->getDeclarations() as $declaration) {
            if ($declaration->isDeductible()) {
                $storer->add($declaration->getDeclaration()->getId());
            }
        }

        return $storer;
    }

    /**
     * @param Bag $bag
     *
     * @return Storer
     */
    private function createDeclarationStorer(Bag $bag)
    {
        $storer = new Storer();

        foreach ($bag->getDeclarations() as $declaration) {
            $storer->add($declaration->getDeclaration());
        }

        return $storer;
    }

    /**
     * @param Bag $bag
     *
     * @return TagStorer
     */
    private function createTagStorer(Bag $bag)
    {
        $storer = new TagStorer();

        foreach ($bag->getDeclarations() as $declaration) {
            foreach ($declaration->getTags() as $tag) {
                $storer->add(
                    $declaration->getDeclaration()->getId(),
                    isset($tag['key']) ? $tag['key'] : $declaration->getDeclaration()->getId(),
                    isset($tag['name']) ? $tag['name'] : $tag
                );
            }
        }

        return $storer;
    }

    /**
     * @param Bag $bag
     *
     * @return CallStorer
     */
    private function createCircularCallStorer(Bag $bag)
    {
        $storer = new CallStorer();

        foreach ($bag->getDeclarations() as $declaration) {
            foreach ($declaration->getCircularCalls() as $call) {
                $storer->add($declaration->getDeclaration()->getId(), $call);
            }
        }

        return $storer;
    }

    /**
     * @param Bag $bag
     *
     * @return AliasStorer
     */
    private function createAliasStorer(Bag $bag)
    {
        $storer = new AliasStorer();

        foreach ($bag->getDeclarations() as $declaration) {
            foreach ($declaration->getAliases() as $alias) {
                $storer->add($alias, $declaration->getDeclaration()->getId());
            }
        }

        return $storer;
    }
}
