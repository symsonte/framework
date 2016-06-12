<?php

namespace Symsonte\Http;

use Symsonte\Http\Message\BodyTrait;
use Symsonte\Http\Message\HeadersTrait;
use Symsonte\Http\PostRequest\Field;
use Symsonte\Http\Request\PathTrait;

class PostRequest
{
    use PathTrait;
    use HeadersTrait;
    use BodyTrait;

    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @param string  $path
     * @param array   $headers
     * @param Field[] $fields
     * @param string  $body
     */
    public function __construct(
        $path,
        $headers,
        $fields,
        $body
    ) {
        $this->path = $path;
        $this->headers = $headers;
        $this->fields = $fields;
        $this->body = $body;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasField($key)
    {
        return isset($this->fields[$key]);
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[$field->getKey()] = $field;
    }

    /**
     * @param string $key
     *
     * @return Field
     */
    public function getField($key)
    {
        if ($this->hasField($key) === false) {
            throw new \InvalidArgumentException();
        }

        return $this->fields[$key];
    }
}
