<?php

namespace Symsonte\Http\Server;

interface Request
{
    const METHOD_OPTIONS = 'OPTIONS';

    public function getMethod();

    public function getUri();

    public function getHttpVersion();

    public function getHeaders();

    public function getBody();
}
