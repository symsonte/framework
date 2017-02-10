<?php

namespace Symsonte\Http\Client\Request;

interface Field
{
    public function getKey();

    public function getValue();
}