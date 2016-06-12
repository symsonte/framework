<?php

namespace Symsonte\Call;

use ReflectionMethod;
use ReflectionException;
use LogicException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class ParametersResolver
{
    /**
     * @var ParameterResolver[]
     */
    private $parameterConverters;

    /**
     * @di\arguments({
     *     parameterConverters: '#symsonte.call.parameter_resolver'
     * })
     *
     * @param ParameterResolver[] $parameterConverters
     */
    public function __construct(array $parameterConverters)
    {
        $this->parameterConverters = $parameterConverters;
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return array
     */
    public function resolve(
        object $object,
        string $method
    ) {
        /* Parameters */

        $class = get_class($object);

        try {
            $reflectionMethod = new ReflectionMethod($class, $method);
        } catch (ReflectionException $e) {
            throw new LogicException(null, null, $e);
        }

        $parameters = [];
        $nulls = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameters[] = $parameter->getName();
            $nulls[$parameter->getName()] = $parameter->allowsNull();
        }

        $convertions = [];
        foreach ($this->parameterConverters as $parameterConverter) {
            $convertions = array_merge(
                $convertions,
                $parameterConverter->resolve($class, $method, $parameters)
            );
        }

        $values = [];
        foreach ($parameters as $parameter) {
            if ($nulls[$parameter] === true && !isset($convertions[$parameter])) {
                $convertions[$parameter] = null;
            }

            $values[$parameter] = $convertions[$parameter];
        }

        return $values;
    }
}
