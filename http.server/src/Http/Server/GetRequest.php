<?php

namespace Symsonte\Http\Server;

use Symsonte\Http\Server\Message\HeadersTrait;
use Symsonte\Http\Server\Request\UriTrait;

class GetRequest
{
    use UriTrait;
    use HeadersTrait;

    function __construct($uri, $headers)
    {
        $this->uri = $uri;
        $this->headers = $headers;
    }
}
