<?php

namespace Symsonte\Call\Parameter\Resolution\Resource;

use Symsonte\Call\Parameter\Resolution;
use Symsonte\Resource\Builder;
use Symsonte\Resource\Cacher;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Resource\DelegatorCompiler;
use Symsonte\Resource\DelegatorNormalizer;
use Symsonte\Resource\DelegatorSliceReader;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\SliceReader;
use Symsonte\Resource\UnsupportedDataAndResourceException;
use Symsonte\Resource\UnsupportedMetadataException;
use LogicException;

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
     *     normalizers:  '#symsonte.call.parameter.resolution.resource.normalizer',
     *     compilers:    '#symsonte.call.parameter.resolution.resource.compiler',
     *     cacher:       '@symsonte.resource.ordinary_cacher'
     * })
     *
     * @di\arguments({
     *     builders:     '#symsonte.resource.builder',
     *     slideReaders: '#symsonte.resource.slice_reader',
     *     normalizers:  '#symsonte.call.parameter.resolution.resource.normalizer',
     *     compilers:    '#symsonte.call.parameter.resolution.resource.compiler',
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
     * @return Resolution[]
     */
    public function load($metadata)
    {
        try {
            $resource = $this->builder->build($metadata);
        } catch (UnsupportedMetadataException $e) {
            throw new LogicException(null, null, $e);
        }

        if ($this->cacher->approve($resource)) {
            return unserialize($this->cacher->retrieve($resource));
        }

        $iterator = $this->sliceReader->init($resource);

        $resolutions = [];

        while ($data = $this->sliceReader->current($iterator)) {
            try {
                $normalization = $this->normalizer->normalize($data, $resource);
            } catch (UnsupportedDataAndResourceException $e) {
                throw new LogicException(null, null, $e);
            }
            $compilation = $this->compiler->compile($normalization);
            unset($normalization);

            if ($compilation instanceof Compilation) {
                $resolution = $compilation->getResolution();

                $resolutions[] = $resolution;
            }

            $this->sliceReader->next($iterator);
        }

        $this->cacher->store(
            serialize($resolutions),
            $resource
        );

        return $resolutions;
    }
}
