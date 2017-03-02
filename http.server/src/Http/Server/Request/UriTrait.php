<?php

namespace Symsonte\Http\Server\Request;

trait UriTrait
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
