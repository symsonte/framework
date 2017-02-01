<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration\ScalarArgument;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;
use Symsonte\ServiceKit\Declaration;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class SetupBuilder
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
    public function build()
    {
        $pioneerBuilder = new PioneerBuilder($this->cacheDir);
        $containerBuilder = new ContainerBuilder();
        $bag = new Bag(
            array_merge(
                $pioneerBuilder->build()->getDeclarations(),
                [
                    new Declaration(
                        new ConstructorDeclaration(
                            'symsonte.service_kit.declaration.bag.composer_builder',
                            'Symsonte\ServiceKit\Declaration\Bag\ComposerBuilder',
                            [
                                new ServiceArgument('symsonte.resource.builder'),
                                new ServiceArgument('symsonte.service_kit.resource.loader'),
                                new ScalarArgument($this->cacheDir),
                                new ScalarArgument($this->filters),
                            ]
                        ),
                        true,
                        false,
                        false,
                        [],
                        [],
                        []
                    ),
                ]
            )
        );
        $container = $containerBuilder->build($bag);

        /** @var ComposerBuilder $bagBuilder */
        $bagBuilder = $container->get('symsonte.service_kit.declaration.bag.composer_builder');

        return new Bag(
            array_merge(
                $bagBuilder->build()->getDeclarations(),
                [
                    new Declaration(
                        new ConstructorDeclaration(
                            'symsonte.service_kit.declaration.bag.parameter_builder',
                            'Symsonte\ServiceKit\Declaration\Bag\ParameterBuilder',
                            [
                                new ServiceArgument('symsonte.resource.yaml_file_flat_reader'),
                                new ServiceArgument('symsonte.resource.yaml_file_builder'),
                                new ScalarArgument($this->parametersFile),
                            ]
                        ),
                        true,
                        false,
                        false,
                        [],
                        [],
                        []
                    ),
                ]
            ),
            [
                'cache_dir' => $this->cacheDir,
            ]
        );
    }
}
