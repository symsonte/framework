<?php

namespace Symsonte\Http\Server\Request\Authorization\Resource;

use Symsonte\Http\Server\Request\Authorization;

class Compilation
{
    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @param Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @return Authorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }
}
