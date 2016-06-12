<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ServiceBuilder implements Builder
{
    /**
     * @var string
     */
    private $parametersFile;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string[]
     */
    private $filters;

    /**
     * @param string   $parametersFile
     * @param string   $cacheDir
     * @param string[] $filters
     */
    public function __construct($parametersFile, $cacheDir, array $filters)
    {
        $this->parametersFile = $parametersFile;
        $this->cacheDir = $cacheDir;
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Bag $bag)
    {
        $setupBuilder = new SetupBuilder(
            $this->parametersFile,
            $this->cacheDir,
            $this->filters
        );
        $containerBuilder = new ContainerBuilder();
        $container = $containerBuilder->build($setupBuilder->build());

        /** @var EventBuilder $eventBagBuilder */
        $eventBagBuilder = $container->get('symsonte.service_kit.declaration.bag.event_builder');
        $bag = $eventBagBuilder->build($bag);
        /** @var ParameterBuilder $parameterBagBuilder */
        $parameterBagBuilder = $container->get('symsonte.service_kit.declaration.bag.parameter_builder');
        $bag = $parameterBagBuilder->build($bag);

        return $bag;
    }
}
