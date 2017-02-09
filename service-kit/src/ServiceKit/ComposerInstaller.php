<?php

namespace Symsonte\ServiceKit;

use Composer\Autoload\ClassLoader;
use Composer\Json\JsonFile;
use Composer\Package\CompletePackage;
use Composer\Repository\InstalledFilesystemRepository;
use Symsonte\Resource\Cacher;
use Symsonte\Resource\FileResource;
use Symsonte\Service\Declaration\ScalarArgument;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\Service\ConstructorDeclaration;
use Symsonte\ServiceKit\Resource\Loader;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Resource\DelegatorFlatReader;
use Symsonte\Resource\YamlFileBuilder;
use Symsonte\Resource\YamlFileFlatReader;
use Symsonte\Resource\FilesystemStorer;
use Symsonte\Resource\OrdinaryCacher;
use Symsonte\Resource\YamlDocParser;
use Symsonte\ServiceKit\Resource\AliasesCompiler;
use Symsonte\ServiceKit\Resource\Argument\TaggedServicesCompiler;
use Symsonte\Resource\Cacher\FileModificationTimeApprover;
use Symsonte\ServiceKit\Resource\Cacher\DirModificationTimeApprover as ServiceKitDirModificationTimeApprover;
use Symsonte\ServiceKit\Resource\Cacher\FileModificationTimeApprover as ServiceKitFileModificationTimeApprover;
use Symsonte\ServiceKit\Resource\ServiceCompiler;
use Symsonte\ServiceKit\Resource\Argument\ServiceCompiler as ServiceArgumentCompiler;
use Symsonte\ServiceKit\Resource\Argument\ParameterCompiler as ParameterArgumentCompiler;
use Symsonte\ServiceKit\Resource\ServiceAnnotationFileNormalizer;
use Symsonte\ServiceKit\Resource\AnnotationFileNormalizer;
use Symsonte\Resource\DataSliceReader;
use Symsonte\Resource\DirBuilder;
use Symsonte\Resource\FilesNormalizer;
use Symsonte\Resource\DirSliceReader;
use Symsonte\Resource\FilesSliceReader;
use Symsonte\Resource\AnnotationFileBuilder;
use Symsonte\Resource\AnnotationFileSliceReader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComposerInstaller
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var Cacher
     */
    private $cacher;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;
    
    /**
     * @param string $cache
     */
    public function __construct($cache)
    {
        $this->loader = $this->createLoader($cache);
        $this->cacher = $this->createCacher($cache);
        $this->containerBuilder = new ContainerBuilder();
    }

    /**
     * @param Bag      $bag
     * @param string[] $filters
     *
     * @return Bag
     */
    public function install(Bag $bag, $filters = [])
    {
        /** @var ClassLoader $classLoader */
        $classLoader = include(sprintf("%s/../../../../../../vendor/autoload.php", __DIR__));

        foreach (array_merge($classLoader->getPrefixes(), $classLoader->getPrefixesPsr4()) as $namespace => $dirs) {
            $pass = false;
            foreach ($filters as $filter) {
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
                $internalBag->merge($this->loader->load([
                    'dir' => $dir,
                    'filter' => '*.php',
                    'extra' => [
                        'type' => 'annotation',
                        'annotation' => '/^ds\\\\/'
                    ]
                ]));
            }

            if (!empty($internalBag->getDeclarations())) {
                $bag->merge($internalBag);
            } else {
                $bag->merge(new Bag(
                    [
                        new Declaration(
                            new ConstructorDeclaration(
                                uniqid(),
                                'Symsonte\ServiceKit\RuntimeInstaller',
                                [
                                    new ServiceArgument('symsonte.service_kit.resource.loader'),
                                    new ScalarArgument($dirs)
                                ]
                            ),
                            false,
                            true,
                            false,
                            [['name' => 'symsonte.service_kit.setup_install']],
                            [],
                            []
                        )
                    ]
                ));
            }
        }

        $container = $this->containerBuilder->build($bag);

        /** @var SetupInstallNotifier $setupInstallNotifier */
        $setupInstallNotifier = $container->get('symsonte.service_kit.setup_install_notifier');
        $bag = $setupInstallNotifier->install($bag);

        /** @var SetupUpdateNotifier $setupUpdateNotifier */
        $setupUpdateNotifier = $container->get('symsonte.service_kit.setup_update_notifier');
        $declarations = [];
        foreach ($bag->getDeclarations() as $declaration) {
            $declarations[] = $setupUpdateNotifier->update($declaration);
        }

//        $this->cacher->store($bag, $resource);

        return new Bag(
            $declarations,
            $bag->getParameters()
        );
    }

    /**
     * @param string $cache
     * 
     * @return OrdinaryCacher
     */
    private function createCacher($cache)
    {
        $fileSliceReaders = [
            new AnnotationFileSliceReader(
                new DataSliceReader(),
                new YamlDocParser()
            )
        ];

        $fileNormalizers = [
            new FilesNormalizer(
                [
                    new AnnotationFileNormalizer(
                        [
                            new ServiceAnnotationFileNormalizer()
                        ]
                    )
                ]
            ),
            new AnnotationFileNormalizer(
                [
                    new ServiceAnnotationFileNormalizer()
                ]
            )
        ];

        return new OrdinaryCacher(
            [
                new ServiceKitFileModificationTimeApprover(
                    new FilesystemStorer($cache, 'time'),
                    $fileSliceReaders,
                    $fileNormalizers
                ),
                new ServiceKitDirModificationTimeApprover(
                    new DirSliceReader([new AnnotationFileBuilder()]),
                    new FilesystemStorer($cache),
                    new ServiceKitFileModificationTimeApprover(
                        new FilesystemStorer($cache),
                        $fileSliceReaders,
                        $fileNormalizers
                    )
                )
            ],
            new FilesystemStorer($cache, 'data')
        );
    }
    
    /**
     * @param string $cache
     * 
     * @return Loader
     */
    private function createLoader($cache)
    {
        $fileSliceReaders = [
            new AnnotationFileSliceReader(
                new DataSliceReader(),
                new YamlDocParser()
            )
        ];
        $fileNormalizers = [
            new FilesNormalizer(
                [
                    new AnnotationFileNormalizer(
                        [
                            new ServiceAnnotationFileNormalizer()
                        ]
                    )
                ]
            ),
            new AnnotationFileNormalizer(
                [
                    new ServiceAnnotationFileNormalizer()
                ]
            )
        ];
        
        return new Loader(
            [
                new DirBuilder()
            ],
            [
                new FilesSliceReader(
                    new DirSliceReader([
                        new YamlFileBuilder(),
                        new AnnotationFileBuilder()
                    ]),
                    $fileSliceReaders
                )
            ],
            $fileNormalizers,
            [
                new ServiceCompiler(
                    [
                        new ServiceArgumentCompiler(),
                        new TaggedServicesCompiler(),
                        new ParameterArgumentCompiler()
                    ]
                ),
                new AliasesCompiler()
            ],
            $this->createCacher($cache)
        );
    }
}
