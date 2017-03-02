<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Composer\Autoload\ClassLoader;
use Symsonte\Resource\Builder as ResourceBuilder;
use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration\ScalarArgument;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\ServiceKit\Declaration;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\ServiceKit\Resource\Loader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComposerBuilder
{
    /**
     * @var ResourceBuilder
     */
    private $builder;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string[]
     */
    private $filters;

    /**
     * @param ResourceBuilder $builder
     * @param Loader          $loader
     * @param string          $cacheDir
     * @param string[]        $filters
     */
    public function __construct(
        ResourceBuilder $builder,
        Loader $loader,
        $cacheDir,
        array $filters
    ) {
        $this->builder = $builder;
        $this->loader = $loader;
        $this->cacheDir = $cacheDir;
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        /** @var ClassLoader $classLoader */
        $classLoader = include sprintf('%s/../../../../../../../autoload.php', __DIR__);

        $bag = new Bag();
        foreach (array_merge($classLoader->getPrefixes(), $classLoader->getPrefixesPsr4()) as $namespace => $dirs) {
            $pass = false;

            foreach ($this->filters as $filter) {
                if (strpos($namespace, $filter) !== false) {
                    $pass = true;

                    break;
                }
            }

            if (!$pass) {
                continue;
            }

            $internalBag = new Bag();

            foreach ($dirs as $i => $dir) {
                $metadata = [
                    'dir'    => $dir,
                    'filter' => '*.php',
                    'extra'  => [
                        'type'       => 'annotation',
                        'annotation' => '/^ds\\\\/',
                    ],
                ];

                $internalBag = new Bag(
                    array_merge(
                        $internalBag->getDeclarations(),
                        $this->loader
                            ->load($this->builder->build($metadata))
                            ->getDeclarations()
                    )
                );
            }

            $found = false;
            foreach ($internalBag->getDeclarations() as $declaration) {
                foreach ($declaration->getTags() as $tag) {
                    if ($tag['name'] == 'symsonte.service_kit.declaration.bag.builder') {
                        $found = true;

                        break;
                    }
                }
            }

            if ($found === false) {
                $internalBag = new Bag(
                    array_merge(
                        $internalBag->getDeclarations(),
                        [
                            new Declaration(
                                new ConstructorDeclaration(
                                    uniqid(),
                                    'Symsonte\ServiceKit\Declaration\Bag\DirsBuilder',
                                    [
                                        new ServiceArgument('symsonte.service_kit.resource.loader'),
                                        new ScalarArgument($dirs),
                                    ]
                                ),
                                false,
                                true,
                                false,
                                ['symsonte.service_kit.declaration.bag.builder'],
                                [],
                                []
                            ),
                        ]
                    )
                );
            }

            $bag = new Bag(
                array_merge(
                    $bag->getDeclarations(),
                    $internalBag->getDeclarations()
                ),
                [
                    'cache_dir' => $this->cacheDir,
                ]
            );
        }

        return $bag;
    }
}
