<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface DocParser
{
    /**
     * Parses file and returns annotations of given name.
     * If name is null, it returns all annotations.
     *
     * @param  string      $file
     * @param  string|null $name
     * @return array       The array of annotations
     */
    public function parse($file, $name = null);
}
