<?php

namespace Symsonte\ServiceKit\Resource\Argument;

use Symsonte\Service\Declaration\ParameterArgument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.argument.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.argument.compiler']
 * })
 */
class ParameterCompiler implements Compiler
{
    /**
     * {@inheritdoc}
     */
    public function compile($argument)
    {
        if ($argument[0] != "%" || $argument[strlen($argument) - 1] != "%") {
            throw new UnsupportedArgumentException($argument);
        }

        return new ParameterArgument(substr($argument, 1, -1));
    }
}