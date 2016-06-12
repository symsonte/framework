<?php

namespace Symsonte\Http;

use Symsonte\Http\Message\HeadersTrait;
use Symsonte\Http\Response\StatusTrait;

class StreamedResponse
{
    use StatusTrait;
    use HeadersTrait;

    /**
     * @var string
     */
    private $contentCallback;

    /**
     * @param callable $contentCallback
     * @param array    $headers
     */
    public function __construct($contentCallback, $headers = [])
    {
        $this->contentCallback = $contentCallback;
        $this->headers = $headers;
    }

    public function getContentCallback()
    {
        return $this->contentCallback;
    }
}
