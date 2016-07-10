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
 * @ds\service({
 *     deductible: true
 * })
 *
 * @di\service({
 *     deductible: true
 * })
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
     *
     * @return Bag
     */
    public function load($resource)
    {
        $iterator = $this->sliceReader->init($resource);

        $bag = new Bag();

        while ($data = $this->sliceReader->current($iterator)) {
            $normalization = $this->normalizer->normalize($data, $resource);
            $compilation = $this->compiler->compile($normalization);
            unset($normalization);

            if ($compilation instanceof ServiceCompilation) {
                $bag = new Bag(
                    array_merge(
                        $bag->getDeclarations(),
                        [
                            new Declaration(
                                $compilation->getDeclaration(),
                                $compilation->isDeductible(),
                                $compilation->isPrivate(),
                                $compilation->isDisposable(),
                                $compilation->getTags(),
                                [],
                                $compilation->getCircularCalls()
                            ),
                        ]
                    ),
                    $bag->getParameters()
                );
            } elseif ($compilation instanceof AliasesCompilation) {
                $declarations = [];
                foreach ($compilation->getAliases() as $alias => $id) {
                    if (!$bag->hasDeclaration($id)) {
                        throw new \InvalidArgumentException();
                    }

                    $declaration = $bag->getDeclaration($id);

                    $declarations[$id] = new Declaration(
                        $declaration->getDeclaration(),
                        $declaration->isDeductible(),
                        $declaration->isPrivate(),
                        $declaration->isDisposable(),
                        $declaration->getTags(),
                        array_merge(
                            $declaration->getAliases(),
                            [$alias]
                        ),
                        $declaration->getCircularCalls()
                    );
                }

                $bag = new Bag(
                    array_merge(
                        $bag->getDeclarations(),
                        $declarations
                    ),
                    $bag->getParameters()
                );
            } elseif ($compilation instanceof ImportsCompilation) {
                $resource = $this->builder->build($compilation->getMetadata());

                $bag = new Bag(
                    array_merge(
                        $bag->getDeclarations(),
                        $this->load($resource)->getDeclarations()
                    ),
                    $bag->getParameters()
                );
            }

            $this->sliceReader->next($iterator);
        }

        return $bag;
    }
}
