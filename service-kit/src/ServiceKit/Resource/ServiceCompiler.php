<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Service\Declaration\Call;
use Symsonte\Resource\Compiler;
use Respect\Validation\Validator as V;
use Symsonte\ServiceKit\Resource\Argument\Compiler as ArgumentCompiler;
use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Resource\UnsupportedNormalizationException;
use Symsonte\ServiceKit\Resource\Argument\DelegatorCompiler as DelegatorArgumentCompiler;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.compiler']
 * })
 */
class ServiceCompiler implements Compiler
{
    /**
     * @var ArgumentCompiler
     */
    private $argumentCompiler;

    /**
     * @param array $argumentCompilers
     *
     * @ds\arguments({
     *     argumentCompilers: '#symsonte.service_kit.resource.argument.compiler'
     * })
     *
     * @di\arguments({
     *     argumentCompilers: '#symsonte.service_kit.resource.argument.compiler'
     * })
     */
    function __construct(array $argumentCompilers)
    {
        $this->argumentCompiler = new DelegatorArgumentCompiler($argumentCompilers);
    }

    /**
     * @param ServiceNormalization $normalization
     *
     * @return ServiceCompilation
     *
     * @throws UnsupportedNormalizationException
     */
    public function compile($normalization)
    {
        if ($this->support($normalization) === false) {
            throw new UnsupportedNormalizationException($normalization);
        }

        $this->validate($normalization);

        $arguments = [];
        foreach ($normalization->arguments as $key => $argument) {
            $arguments[$key] = $this->argumentCompiler->compile($argument);
        }

        $calls = [];
        foreach ($normalization->calls as $call) {
            $callArguments = [];
            if (isset($call['arguments'])) {
                foreach ($call['arguments'] as $argument) {
                    $callArguments[] = $this->argumentCompiler->compile($argument);
                }
            }

            $calls[] = new Call($call['method'], $callArguments);
        }

        $circularCalls = [];
        foreach ($normalization->circularCalls as $call) {
            $callArguments = [];
            if (isset($call['arguments'])) {
                foreach ($call['arguments'] as $argument) {
                    $callArguments[] = $this->argumentCompiler->compile($argument);
                }
            }

            $circularCalls[] = new Call($call['method'], $callArguments);
        }
        
        return new ServiceCompilation(
            new ConstructorDeclaration(
                $normalization->id,
                $normalization->class,
                $arguments,
                $calls
            ),
            $normalization->deductible,
            $normalization->private,
            $normalization->disposable,
            $normalization->tags,
            $circularCalls
        );
    }

    private function support($declaration)
    {
        return $declaration instanceof ServiceNormalization;
    }

    /**
     * @param ServiceNormalization $declaration
     */
    private function validate(ServiceNormalization $declaration)
    {
//        V::key(
//            V::key('foo', v::intVal()),
//            V::key('bar', v::stringType()),
//            V::key('baz', v::boolType())
//        )->validate($dict);
//
//        (new ExceptionValidator(new ObjectValidator(array(
//            'id' => new ValueValidator(array(
//                'type' => 'string'
//            )),
//            'class' => new ValueValidator(array(
//                'type' => 'string'
//            )),
//            'arguments' => new ArrayValidator(array(
//                'map' => function($e) {
//                    if (!is_string($e)) {
//                        return 'Invalid element';
//                    }
//
//
//                    return null;
//                }
//            )),
//            // TODO: Validate calls
//            'deductible' => new ValueValidator(array(
//                'type' => 'boolean'
//            )),
//            'private' => new ValueValidator(array(
//                'type' => 'boolean'
//            )),
//            'disposable' => new ValueValidator(array(
//                'type' => 'boolean'
//            )),
//            'tags' => new ArrayValidator(array(
//                'map' => function($e) {
//                    if (!is_string($e)) {
//                        return 'Invalid element';
//                    }
//
//                    return null;
//                }
//            ))
//        ))))->validate($declaration);
    }
}