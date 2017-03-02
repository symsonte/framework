<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\Service\Declaration\TaggedServicesArgument;
use Symsonte\ServiceKit\Declaration;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PioneerBuilder
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param string $cacheDir
     */
    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function build()
    {
        return new Bag(
            array_merge(
                $this->buildBuilderDeclarations(),
                $this->buildLoaderDeclarations()
            )
        );
    }

    /**
     * @return Declaration[]
     */
    private function buildBuilderDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.yaml_file_builder',
                    'Symsonte\Resource\YamlFileBuilder',
                    []
                ),
                true,
                false,
                false,
                ['symsonte.resource.builder'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.annotation_file_builder',
                    'Symsonte\Resource\AnnotationFileBuilder',
                    []
                ),
                true,
                false,
                false,
                ['symsonte.resource.builder'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.dir_builder',
                    'Symsonte\Resource\DirBuilder',
                    []
                ),
                true,
                false,
                false,
                ['symsonte.resource.builder'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.builder',
                    'Symsonte\Resource\DelegatorBuilder',
                    [
                        new TaggedServicesArgument('symsonte.resource.builder'),
                    ]
                ),
                true,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildLoaderDeclarations()
    {
        return array_merge(
            $this->buildReaderDeclarations(),
            $this->buildNormalizerDeclarations(),
            $this->buildCompilerDeclarations(),
            [
                new Declaration(
                    new ConstructorDeclaration(
                        'symsonte.service_kit.resource.loader',
                        'Symsonte\ServiceKit\Resource\Loader',
                        [
                            new TaggedServicesArgument('symsonte.resource.builder'),
                            new TaggedServicesArgument('symsonte.resource.slice_reader'),
                            new TaggedServicesArgument('symsonte.service_kit.resource.normalizer'),
                            new TaggedServicesArgument('symsonte.service_kit.resource.compiler'),
                        ]
                    ),
                    false,
                    true,
                    false,
                    [],
                    [],
                    []
                ),
            ]
        );
    }

    /**
     * @return Declaration[]
     */
    private function buildReaderDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.data_slice_reader',
                    'Symsonte\Resource\DataSliceReader'
                ),
                false,
                true,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.yaml_doc_parser',
                    'Symsonte\Resource\YamlDocParser'
                ),
                false,
                true,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.annotation_file_slice_reader',
                    'Symsonte\Resource\AnnotationFileSliceReader',
                    [
                        new ServiceArgument('symsonte.resource.data_slice_reader'),
                        new ServiceArgument('symsonte.resource.yaml_doc_parser'),
                    ]
                ),
                false,
                true,
                false,
                ['symsonte.resource.file_slice_reader', 'symsonte.resource.slice_reader'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.yaml_file_slice_reader',
                    'Symsonte\Resource\YamlFileSliceReader',
                    [
                        new ServiceArgument('symsonte.resource.data_slice_reader'),
                    ]
                ),
                false,
                true,
                false,
                ['symsonte.resource.file_slice_reader', 'symsonte.resource.slice_reader'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.dir_slice_reader',
                    'Symsonte\Resource\DirSliceReader',
                    [
                        new TaggedServicesArgument('symsonte.resource.builder'),
                    ]
                ),
                false,
                true,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.files_slice_reader',
                    'Symsonte\Resource\FilesSliceReader',
                    [
                        new ServiceArgument('symsonte.resource.dir_slice_reader'),
                        new TaggedServicesArgument('symsonte.resource.file_slice_reader'),
                    ]
                ),
                false,
                true,
                false,
                ['symsonte.resource.slice_reader'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.slice_reader',
                    'Symsonte\Resource\DelegatorSliceReader',
                    [
                        new TaggedServicesArgument('symsonte.resource.slice_reader'),
                    ]
                ),
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildNormalizerDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.service_annotation_file_normalizer',
                    'Symsonte\ServiceKit\Resource\ServiceAnnotationFileNormalizer'
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.annotation_file_normalizer'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.annotation_file_normalizer',
                    'Symsonte\ServiceKit\Resource\AnnotationFileNormalizer',
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.annotation_file_normalizer'),
                    ]
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.file_normalizer', 'symsonte.service_kit.resource.normalizer'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.files_normalizer',
                    'Symsonte\ServiceKit\Resource\FilesNormalizer',
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.file_normalizer'),
                    ]
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.normalizer'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.normalizer',
                    'Symsonte\Resource\DelegatorNormalizer',
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.normalizer'),
                    ]
                ),
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildCompilerDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.argument.service_compiler',
                    'Symsonte\ServiceKit\Resource\Argument\ServiceCompiler'
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.argument.compiler'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.argument.tagged_services_compiler',
                    'Symsonte\ServiceKit\Resource\Argument\TaggedServicesCompiler'
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.argument.compiler'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.argument.parameter_compiler',
                    'Symsonte\ServiceKit\Resource\Argument\ParameterCompiler'
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.argument.compiler'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.service_compiler',
                    'Symsonte\ServiceKit\Resource\ServiceCompiler',
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.argument.compiler'),
                    ]
                ),
                false,
                true,
                false,
                ['symsonte.service_kit.resource.compiler'],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.compiler',
                    'Symsonte\Resource\DelegatorCompiler',
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.compiler'),
                    ]
                ),
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }
}
