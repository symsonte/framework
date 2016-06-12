<?php

namespace Symsonte\Http\Response;

trait StatusTrait
{
    /**
     * @var int
     */
    private $status;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
