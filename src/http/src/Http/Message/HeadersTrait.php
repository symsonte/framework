<?php

namespace Symsonte\Http\Message;

trait HeadersTrait
{
    /**
     * @var string[]
     */
    private $headers;

    /**
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getHeader($key)
    {
        if ($this->hasHeader($key) === false) {
            throw new \InvalidArgumentException();
        }

        return $this->headers[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasHeader($key)
    {
        return isset($this->headers[$key]);
    }
}
