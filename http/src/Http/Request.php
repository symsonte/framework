<?php

namespace Symsonte\Http;

interface Request
{
    const METHOD_OPTIONS = 'OPTIONS';

    public function getMethod();

    public function getPath();

    public function getHttpVersion();

    public function getHeaders();

    public function getBody();
}
