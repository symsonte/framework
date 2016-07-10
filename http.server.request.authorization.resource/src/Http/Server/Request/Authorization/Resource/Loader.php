<?php

namespace Symsonte\Http\Server\Request\Authorization\Resource;

use Symsonte\Http\Server\Request\Authorization\Bag;
use Symsonte\Resource\Builder;
use Symsonte\Resource\Cacher;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Resource\DelegatorCompiler;
use Symsonte\Resource\DelegatorNormalizer;
use Symsonte\Resource\DelegatorSliceReader;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\SliceReader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
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
     * @var BaseCompiler
     */
    private $compiler;

    /**
     * @var Cacher
     */
    private $cacher;

    /**
     * @param Builder[]      $builders
     * @param SliceReader[]  $sliceReaders
     * @param Normalizer[]   $normalizers
     * @param BaseCompiler[] $compilers
     * @param Cacher         $cacher
     *
     * @ds\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     slideReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.http.server.request.authorization.resource.normalizer',
     *     compilers:    '#symsonte.http.server.request.authorization.resource.compiler',
     *     cacher:       '@symsonte.resource.ordinary_cacher'
     * })
     *
     * @di\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     slideReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.http.server.request.authorization.resource.normalizer',
     *     compilers:    '#symsonte.http.server.request.authorization.resource.compiler',
     *     cacher:       '@symsonte.resource.ordinary_cacher'
     * })
     */
    public function __construct(
        array $builders,
        array $sliceReaders,
        array $normalizers,
        array $compilers,
        Cacher $cacher
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
            return unserialize($this->cacher->retrieve($resource));
        }

        $iterator = $this->sliceReader->init($resource);

        $bag = new Bag();

        while ($data = $this->sliceReader->current($iterator)) {
            $normalization = $this->normalizer->normalize($data, $resource);
            $compilation = $this->compiler->compile($normalization);
            unset($normalization);

            if ($compilation instanceof Compilation) {
                $bag->add($compilation->getAuthorization());
            }

            $this->sliceReader->next($iterator);
        }

        $this->cacher->store(
            serialize($bag),
            $resource
        );

        return $bag;
    }
}
