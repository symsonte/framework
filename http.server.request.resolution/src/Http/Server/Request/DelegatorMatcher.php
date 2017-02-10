<?php

namespace Symsonte\Http\Server\Request;

class DelegatorMatcher implements Matcher
{
    /**
     * @var Matcher[]
     */
    private $matchers;

    /**
     * @param Matcher[]|null $matchers
     */
    public function __construct(array $matchers = [])
    {
        $this->matchers = $matchers;
    }

    /**
     * {@inheritdoc}
     */
    public function match($match, $request)
    {
        foreach ($this->matchers as $matcher) {
            try {
                return $matcher->match($match, $request);
            } catch (UnsupportedMatchException $e) {
                continue;
            }
        }

        throw new UnsupportedMatchException($match);
    }
}
