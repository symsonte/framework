<?php

namespace Symsonte\Http\Server;

use Symsonte\Http\Server\Message\HeadersTrait;
use Symsonte\Http\Server\Request\UriTrait;

class OptionsRequest
{
    use UriTrait;
    use HeadersTrait;

    public function __construct($uri, $headers)
    {
        $this->uri = $uri;
        $this->headers = $headers;
    }
}
