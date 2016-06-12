<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PrematureResponseException extends \Exception
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $body;

    /**
     * @param array  $headers
     * @param int    $status
     * @param string $body
     */
    public function __construct(array $headers, $status, $body)
    {
        parent::__construct();

        $this->headers = $headers;
        $this->status = $status;
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
