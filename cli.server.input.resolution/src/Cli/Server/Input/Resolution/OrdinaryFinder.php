<?php

namespace Symsonte\Cli\Server\Input\Resolution;

use Symsonte\Cli\Server\Input\DelegatorMatcher;
use Symsonte\Cli\Server\Input\Matcher;

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
class OrdinaryFinder implements Finder
{
    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @var Bag
     */
    private $bag;

    /**
     * @param Matcher[] $matchers
     *
     * @ds\arguments({
     *     matchers: '#symsonte.cli.server.input.matcher'
     * })
     *
     * @di\arguments({
     *     matchers: '#symsonte.cli.server.input.matcher'
     * })
     */
    function __construct(array $matchers)
    {
        $this->matcher = new DelegatorMatcher($matchers);
        $this->bag = new Bag();
    }

    /**
     * {@inheritdoc}
     */
    public function first($input)
    {
        foreach ($this->bag->all() as $key => $resolution) {
            foreach ($resolution->getMatches() as $match) {
                if ($this->matcher->match($match, $input) !== true) {
                    continue 2;
                }

                return $key;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(Bag $bag)
    {
        $this->bag->merge($bag);
    }
}