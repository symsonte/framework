<?php

namespace Symsonte\Http\Server\Response;

interface Sender
{
    public function support($response);

    public function send($response);
}