<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Resource\YamlFileBuilder;
use Symsonte\Resource\YamlFileFlatReader;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ParameterBuilder implements Builder
{
    /**
     * @var YamlFileFlatReader
     */
    private $reader;

    /**
     * @var YamlFileBuilder
     */
    private $builder;

    /**
     * @var string
     */
    private $parametersFile;

    /**
     * @param YamlFileFlatReader $reader
     * @param YamlFileBuilder    $builder
     * @param string             $parametersFile
     */
    public function __construct(
        YamlFileFlatReader $reader,
        YamlFileBuilder $builder,
        $parametersFile
    ) {
        $this->reader = $reader;
        $this->builder = $builder;
        $this->parametersFile = $parametersFile;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Bag $bag)
    {
        $parameters = $this->reader->read(
            $this->builder->build(
                $this->parametersFile
            )
        );

        $parsedParameters = [];
        foreach ($parameters as $key => $value) {
            $parsedParameters[$key] = str_replace('__DIR__', dirname($this->parametersFile), $value);
        }

        return new Bag(
            $bag->getDeclarations(),
            array_merge(
                $bag->getParameters(),
                $parsedParameters
            )
        );
    }
}
