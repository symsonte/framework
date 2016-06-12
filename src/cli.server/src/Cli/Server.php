<?php

namespace Symsonte\Cli;

use Aura\Cli\CliFactory;
use Aura\Cli\Context\Getopt;
use Aura\Cli\Stdio;

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
class Server
{
    /**
     * @var Getopt
     */
    private $input;

    /**
     * @var Stdio
     */
    private $output;

    /**
     * @return Getopt
     */
    public function resolveInput()
    {
        if (!$this->input) {
            $this->input = (new CliFactory())->newContext($GLOBALS)->getopt([]);
        }

        return $this->input;
    }

    /**
     * @return Stdio
     */
    public function resolveOutput()
    {
        if (!$this->output) {
            $this->output = (new CliFactory)->newStdio();
        }

        return $this->output;
    }
}
