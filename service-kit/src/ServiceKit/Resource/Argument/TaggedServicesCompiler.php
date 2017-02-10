<?php

namespace Symsonte\ServiceKit\Resource\Argument;

use Symsonte\Service\Declaration\TaggedServicesArgument;

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
class TaggedServicesCompiler implements Compiler
{
    /**
     * {@inheritdoc}
     */
    public function compile($argument)
    {
        if ($argument[0] != "#") {
            throw new UnsupportedArgumentException($argument);
        }

        return new TaggedServicesArgument(substr($argument, 1));
    }
}