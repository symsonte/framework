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
            $parameters[] = [
                'name' => $parameter->getName(),
                'variadic' => $parameter->isVariadic()
            ];

            try {
                $nulls[$parameter->getName()] = $parameter->getDefaultValue();
            } catch (ReflectionException) {
                continue;
            }
        }

        $conversions = [];
        foreach ($this->parameterConverters as $parameterConverter) {
            $conversions = array_merge(
                $conversions,
                $parameterConverter->resolve($class, $method, $parameters)
            );
        }

        $values = [];
        foreach ($parameters as $parameter) {
            if (isset($nulls[$parameter['name']]) && !isset($conversions[$parameter['name']])) {
                $conversions[$parameter['name']] = $nulls[$parameter['name']];
            }

            if ($parameter['variadic']) {
                continue;
            }

            $values[$parameter['name']] = $conversions[$parameter['name']];

            unset($conversions[$parameter['name']]);
        }

        foreach ($parameters as $parameter) {
            if (!$parameter['variadic']) {
                continue;
            }

            // Merge the rest of $conversions
            $values = array_merge(
                $values,
                $conversions
            );
        }

        return $values;
    }
}
