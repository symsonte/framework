<?php

namespace Symsonte\Cli\Server\Input;

use Aura\Cli\CliFactory;
use Aura\Cli\Context;
use Aura\Cli\Context\Getopt;

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
class AuraResolver implements Resolver
{
    /**
     * @var
     */
    private $input;

    /**
     * @return mixed
     */
    public function resolve()
    {
        if (isset($this->input)) {
            return $this->input;
        }

        /** @var Context $context */
        $context = (new CliFactory())->newContext($GLOBALS);
        /** @var Getopt $input */
        $input = $context->getopt([]);

        $this->input = $input;

        return $input;
    }
}
