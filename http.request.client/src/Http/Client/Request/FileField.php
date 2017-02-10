<?php

namespace Symsonte\Http\Client\Request;

class FileField implements Field
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var \SplFileObject
     */
    private $value;

    /**
     * @param string         $key
     * @param \SplFileObject $value
     */
    function __construct($key, \SplFileObject $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return \SplFileObject
     */
    public function getValue()
    {
        return $this->value;
    }
}