<?php

namespace Symsonte\Assembly;

interface Worker
{
    public function setNext(Worker $worker);

    public function work();
}
