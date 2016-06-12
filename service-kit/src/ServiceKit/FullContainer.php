<?php

namespace Symsonte\ServiceKit;

use Symsonte\Resource\Cacher;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Resource\DelegatorFlatReader;
use Symsonte\Resource\YamlFileBuilder;
use Symsonte\Resource\YamlFileFlatReader;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\Service\Container;
use Symsonte\Resource\FilesystemStorer;
use Symsonte\Resource\OrdinaryCacher;
use Symsonte\Resource\YamlDocParser;
use Symsonte\ServiceKit\Resource\AliasesCompiler;
use Symsonte\ServiceKit\Resource\Argument\TaggedServicesCompiler;
use Symsonte\Resource\Cacher\FileModificationTimeApprover;
use Symsonte\ServiceKit\Resource\Cacher\DirModificationTimeApprover as ServiceKitDirModificationTimeApprover;
use Symsonte\ServiceKit\Resource\Cacher\FileModificationTimeApprover as ServiceKitFileModificationTimeApprover;
use Symsonte\ServiceKit\Resource\ServiceCompiler;
use Symsonte\ServiceKit\Resource\Loader;
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
use Symsonte\Service\AliasContainer;
use Symsonte\Service\CachedInstantiator;
use Symsonte\Service\ConstructorInstantiator;
use Symsonte\Service\Declaration\Argument\DelegatorProcessor;
use Symsonte\Service\Declaration\Argument\ObjectProcessor;
use Symsonte\Service\Declaration\Argument\ServiceProcessor as ServiceArgumentProcessor;
use Symsonte\Service\Declaration\Argument\ParameterProcessor as ParameterArgumentProcessor;
use Symsonte\Service\Declaration\Argument\ScalarProcessor as ScalarArgumentProcessor;
use Symsonte\Service\Declaration\Argument\TaggedServicesProcessor;
use Symsonte\Service\Declaration\Call\Processor as CallProcessor;
use Symsonte\Service\Declaration\IdStorer;
use Symsonte\Service\Declaration\ParameterStorer;
use Symsonte\Service\DeductibleContainer;
use Symsonte\Service\DelegatorContainer;
use Symsonte\Service\ObjectContainer;
use Symsonte\Service\ObjectStorer;
use Symsonte\Service\OrdinaryContainer;
use Symsonte\Service\PrivateContainer;
use Symsonte\ConstructorInstantiator as BaseConstructorInstantiator;
use Symsonte\Service\Declaration\Storer;
use Symsonte\Service\Declaration\AliasStorer;
use Symsonte\Service\Declaration\TagStorer;

class FullContainer implements Container
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param string $currentDir
     * @param string $parametersFile
     */
    public function __construct($currentDir, $parametersFile)
    {
        $builders = [
            new YamlFileBuilder(),
            new AnnotationFileBuilder()
        ];

        $flatReader = new DelegatorFlatReader([
            new YamlFileFlatReader()
        ]);

        $parameters = $flatReader->read(
            (new DelegatorBuilder($builders))->build($parametersFile)
        );
        foreach ($parameters as $i => $parameter) {
            $parameters[$i] = str_replace('__DIR__', $currentDir, $parameter);
        }

        $bag = new Bag(
            [],
            $parameters
        );

        $storer = new FilesystemStorer($parameters['cache_dir']);

        if ($storer->has('container')) {
            $this->container = $storer->get('container');

            return;
        }

        $fileCacher = new OrdinaryCacher(
            [
                new FileModificationTimeApprover(
                    new FilesystemStorer($parameters['cache_dir'], 'time')
                )
            ],
            new FilesystemStorer($parameters['cache_dir'], 'data')
        );
        $fileSliceReaders = [
            new AnnotationFileSliceReader(
                new DataSliceReader(),
                new YamlDocParser()
            )
        ];
        $sliceReaders = [
            new FilesSliceReader(
                new DirSliceReader($builders),
                $fileSliceReaders
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
        $serviceCacher = new OrdinaryCacher(
            [
                new ServiceKitFileModificationTimeApprover(
                    new FilesystemStorer($parameters['cache_dir'], 'time'),
                    $fileSliceReaders,
                    $fileNormalizers
                ),
                new ServiceKitDirModificationTimeApprover(
                    new DirSliceReader([new AnnotationFileBuilder()]),
                    new FilesystemStorer($parameters['cache_dir']),
                    new ServiceKitFileModificationTimeApprover(
                        new FilesystemStorer($parameters['cache_dir']),
                        $fileSliceReaders,
                        $fileNormalizers
                    )
                )
            ],
            new FilesystemStorer($parameters['cache_dir'], 'data')
        );
        $serviceLoader = new Loader(
            [
                new DirBuilder()
            ],
            $sliceReaders,
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
            $serviceCacher
        );
        $composerInstaller = new ComposerInstaller(
            $serviceLoader,
            $fileCacher
        );

        $bag = $composerInstaller->install($bag);

        $container = $this->buildContainer($bag);

        /** @var SetupInstallNotifier $setupInstallNotifier */
        $setupInstallNotifier = $container->get('symsonte.service_kit.setup_install_notifier');
        $bag = $setupInstallNotifier->start($bag);

        /** @var SetupUpdateNotifier $setupUpdateNotifier */
        $setupUpdateNotifier = $container->get('symsonte.service_kit.setup_update_notifier');
        $declarations = [];
        foreach ($bag->getDeclarations() as $declaration) {
            $declarations[] = $setupUpdateNotifier->update($declaration);
        }

        $container = $this->buildContainer(
            new Bag(
                $declarations,
                $parameters
            )
        );

        $this->container = $container;

        $storer->add($container, 'container');
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param Bag $bag
     *
     * @return PrivateContainer
     */
    private function buildContainer(Bag $bag)
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
            $scalarArgumentProcessor
        ]);
        $declarationStorer = $this->createDeclarationStorer($bag);
        $objectContainer = new ObjectContainer(new ObjectStorer());
        $container = new DelegatorContainer([
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
                                new CallProcessor($argumentProcessor),
                                new BaseConstructorInstantiator()
                            )
                        )
                    )
                )
            ),
            $objectContainer
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
                $storer->add($declaration->getDeclaration()->getId(), $tag);
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