<?php

namespace Symsonte\Resource;

interface Definition
{
    /**
     * @param mixed $data
     */
    public function import($data);

    /**
     * @return mixed
     */
    public function export();
}
