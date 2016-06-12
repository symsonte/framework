<?php

namespace Symsonte\Cli\Server\Input;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.matcher']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.matcher']
 * })
 */
class FirstParameterEqualMatcher implements Matcher
{
    /**
     * {@inheritdoc}
     */
    public function match($match, $input)
    {
        if (!$match instanceof FirstParameterMatch) {
            throw new UnsupportedMatchException($match);
        }

        return $match->getPattern() === $input->get(1);
    }
}
