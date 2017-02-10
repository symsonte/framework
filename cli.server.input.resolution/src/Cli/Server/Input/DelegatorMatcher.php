<?php

namespace Symsonte\Cli\Server\Input;

class DelegatorMatcher implements Matcher
{
    /**
     * @var Matcher[]
     */
    private $matchers;

    /**
     * @param Matcher[]|null $matchers
     */
    function __construct(array $matchers = [])
    {
        $this->matchers = $matchers;
    }

    /**
     * {@inheritdoc}
     */
    public function match($match, $input)
    {
        foreach ($this->matchers as $matcher) {
            try {
                return $matcher->match($match, $input);
            } catch (UnsupportedMatchException $e) {
                continue;
            }
        }

        throw new UnsupportedMatchException($match);
    }
}