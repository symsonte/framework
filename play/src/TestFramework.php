<?php

namespace Symsonte\Test;

/**
 * @di\service()
 */
class TestFramework
{
    /**
     * @cli\resolution({command: "/test"})
     */
    public function test() {
        echo "ok";
    }
}