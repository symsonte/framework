<?php

namespace Symsonte\Http\Message;

trait BodyTrait
{
    /**
     * @var string
     */
    private $body;

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
