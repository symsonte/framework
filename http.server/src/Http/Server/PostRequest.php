<?php

namespace Symsonte\Http\Server;

use Symsonte\Http\Server\Message\BodyTrait;
use Symsonte\Http\Server\Message\HeadersTrait;
use Symsonte\Http\Server\PostRequest\Field;
use Symsonte\Http\Server\Request\UriTrait;

class PostRequest
{
    use UriTrait;
    use HeadersTrait;
    use BodyTrait;

    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @param string  $uri
     * @param array   $headers
     * @param Field[] $fields
     * @param string  $body
     */
    public function __construct(
        $uri,
        $headers,
        $fields,
        $body
    ) {
        $this->uri = $uri;
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
