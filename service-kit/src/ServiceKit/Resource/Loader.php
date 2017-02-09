<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Builder;
use Symsonte\Resource\Cacher;
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
     * @var Cacher
     */
    private $cacher;

    /**
     * @param Builder[]     $builders
     * @param SliceReader[] $sliceReaders
     * @param Normalizer[]  $normalizers
     * @param Compiler[]    $compilers
     * @param Cacher[]      $cacher
     *
     * @ds\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     sliceReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.service_kit.resource.normalizer',
     *     compilers:    '#symsonte.service_kit.resource.compiler',
     *     cacher:       '@symsonte.resource.ordinary_cacher'
     * })
     *
     * @di\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     sliceReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.service_kit.resource.normalizer',
     *     compilers:    '#symsonte.service_kit.resource.compiler',
     *     cacher:       '@symsonte.resource.ordinary_cacher'
     * })
     */
    public function __construct(
        array $builders,
        array $sliceReaders,
        array $normalizers,
        array $compilers,
        $cacher
    ) {
        $this->builder = new DelegatorBuilder($builders);
        $this->sliceReader = new DelegatorSliceReader($sliceReaders);
        $this->normalizer = new DelegatorNormalizer($normalizers);
        $this->compiler = new DelegatorCompiler($compilers);
        $this->cacher = $cacher;
    }

    /**
     * @param mixed $metadata
     *
     * @return Bag
     */
    public function load($metadata)
    {
        $resource = $this->builder->build($metadata);

        if ($this->cacher->approve($resource)) {
            return $this->cacher->retrieve($resource);
        }

        $iterator = $this->sliceReader->init($resource);

        $bag = new Bag();

        while ($data = $this->sliceReader->current($iterator)) {
            $normalization = $this->normalizer->normalize($data, $resource);
            $compilation = $this->compiler->compile($normalization);
            unset($normalization);

            if ($compilation instanceof ServiceCompilation) {
                $bag->addDeclaration(
                    new Declaration(
                        $compilation->getDeclaration(),
                        $compilation->isDeductible(),
                        $compilation->isPrivate(),
                        $compilation->isDisposable(),
                        $compilation->getTags(),
                        [],
                        $compilation->getCircularCalls()
                    )
                );
            } elseif ($compilation instanceof AliasesCompilation) {
                $bag->addAliases(
                    $compilation->getAliases()
                );
            } elseif ($compilation instanceof ImportsCompilation) {
                $bag->merge(
                    $this->load($compilation->getMetadata())
                );
            }

            $this->sliceReader->next($iterator);
        }

        $this->cacher->store(
            $bag,
            $resource
        );

        return $bag;
    }
}
