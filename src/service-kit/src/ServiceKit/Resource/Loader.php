<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Builder;
use Symsonte\Resource\Compiler;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Resource\DelegatorCompiler;
use Symsonte\Resource\DelegatorNormalizer;
use Symsonte\Resource\DelegatorSliceReader;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\SliceReader;
use Symsonte\ServiceKit\Declaration;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service()
 *
 * @di\service()
 */
class Loader
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var SliceReader
     */
    private $sliceReader;

    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @param Builder[]     $builders
     * @param SliceReader[] $sliceReaders
     * @param Normalizer[]  $normalizers
     * @param Compiler[]    $compilers
     *
     * @ds\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     sliceReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.service_kit.resource.normalizer',
     *     compilers:    '#symsonte.service_kit.resource.compiler',
     * })
     *
     * @di\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     sliceReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.service_kit.resource.normalizer',
     *     compilers:    '#symsonte.service_kit.resource.compiler',
     * })
     */
    public function __construct(
        array $builders,
        array $sliceReaders,
        array $normalizers,
        array $compilers
    ) {
        $this->builder = new DelegatorBuilder($builders);
        $this->sliceReader = new DelegatorSliceReader($sliceReaders);
        $this->normalizer = new DelegatorNormalizer($normalizers);
        $this->compiler = new DelegatorCompiler($compilers);
    }

    /**
     * @param mixed $resource
     * @param Bag   $resource
     *
     * @return Bag
     */
    public function load($resource, $bag)
    {
        $iterator = $this->sliceReader->init($resource);

        while ($data = $this->sliceReader->current($iterator)) {
            $normalization = $this->normalizer->normalize($data, $resource);
            $compilation = $this->compiler->compile($normalization);
            unset($normalization);

            if ($compilation instanceof ServiceCompilation) {
                $bag = new Bag(
                    array_merge(
                        $bag->getDeclarations(),
                        [
                            $compilation->getDeclaration()->getId() => new Declaration(
                                $compilation->getDeclaration(),
                                $compilation->isDeductible(),
                                $compilation->isPrivate(),
                                $compilation->isDisposable(),
                                $compilation->isLazy(),
                                $compilation->getTags(),
                                [],
                                $compilation->getCircularCalls()
                            ),
                        ]
                    ),
                    $bag->getParameters(),
                    $bag->getInstances()
                );
            } elseif ($compilation instanceof AliasesCompilation) {
                foreach ($compilation->getAliases() as $alias => $id) {
                    if (!$bag->hasDeclaration($id)) {
                        throw new \InvalidArgumentException();
                    }

                    $declaration = $bag->getDeclaration($id);

                    $bag = new Bag(
                        array_merge(
                            $bag->getDeclarations(),
                            [
                                $alias => new Declaration(
                                    $declaration->getDeclaration(),
                                    $declaration->isDeductible(),
                                    $declaration->isPrivate(),
                                    $declaration->isLazy(),
                                    $declaration->isDisposable(),
                                    $declaration->getTags(),
                                    array_merge(
                                        $declaration->getAliases(),
                                        [$alias]
                                    ),
                                    $declaration->getCircularCalls()
                                ),
                            ]
                        ),
                        $bag->getParameters(),
                        $bag->getInstances()
                    );
                }
            } elseif ($compilation instanceof ImportsCompilation) {
                $resource = $this->builder->build($compilation->getMetadata());

                $bag = $this->load($resource, $bag);
            }

            $this->sliceReader->next($iterator);
        }

        return $bag;
    }
}
