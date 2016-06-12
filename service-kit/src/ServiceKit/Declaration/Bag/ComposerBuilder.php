<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Composer\Autoload\ClassLoader;
use Symsonte\Resource\Builder as ResourceBuilder;
use Symsonte\Resource\Cacher;
use Symsonte\Resource\FileResource;
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
     * @var Cacher
     */
    private $cacher;

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
     * @param Cacher          $cacher
     * @param ResourceBuilder $builder
     * @param Loader          $loader
     * @param string          $cacheDir
     * @param string[]        $filters
     */
    public function __construct(
        Cacher $cacher,
        ResourceBuilder $builder,
        Loader $loader,
        $cacheDir,
        array $filters
    ) {
        $this->cacher = $cacher;
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
        $resource = new FileResource( sprintf('%s/../../../../../../../../composer.lock', __DIR__));

        if ($this->cacher->approve($resource)) {
            return $this->cacher->retrieve($resource);
        }

        /** @var ClassLoader $classLoader */
        $classLoader = include sprintf('%s/../../../../../../../autoload.php', __DIR__);

        $bag = new Bag();
        // Tag counter
        $t = 0;
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

                $internalBag = $this->loader->load(
                    $this->builder->build($metadata),
                    $internalBag
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
                $id = uniqid();

                $t++;

                $internalBag = new Bag(
                    array_merge(
                        $internalBag->getDeclarations(),
                        [
                            $id => new Declaration(
                                new ConstructorDeclaration(
                                    $id,
                                    'Symsonte\ServiceKit\Declaration\Bag\DirsBuilder',
                                    false,
                                    [
                                        new ServiceArgument('symsonte.service_kit.resource.cached_loader'),
                                        new ScalarArgument($dirs),
                                    ]
                                ),
                                false,
                                false,
                                true,
                                false,
                                [
                                    ['key' => $t, 'name' => 'symsonte.service_kit.declaration.bag.builder']
                                ],
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

        $this->cacher->store($bag, $resource);

        return $bag;
    }
}
