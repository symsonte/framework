<?php

namespace Symsonte\Http\Server\Request\Resolution;

use Symsonte\Http\Server\Request\DelegatorMatcher;
use Symsonte\Http\Server\Request\Matcher;

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
     *     matchers: '#symsonte.http.server.request.matcher'
     * })
     *
     * @di\arguments({
     *     matchers: '#symsonte.http.server.request.matcher'
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
    public function first($request)
    {
        foreach ($this->bag->all() as $key => $resolution) {
            foreach ($resolution->getMatches() as $match) {
                if ($this->matcher->match($match, $request) !== true) {
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