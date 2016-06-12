<?php

namespace Symsonte\Http;

use Symsonte\Http\Message\HeadersTrait;
use Symsonte\Http\Request\PathTrait;

class OptionsRequest
{
    use PathTrait;
    use HeadersTrait;

    public function __construct($path, $headers)
    {
        $this->path = $path;
        $this->headers = $headers;
    }
}
