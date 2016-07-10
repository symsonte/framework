<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Instantiator
{
    /**
     * Returns whether given declaration is supported.
     *
     * @param Declaration $declaration
     *
     * @return bool
     */
    public function support($declaration);

    /**
     * Instantiates given declaration.
     *
     * @param Declaration $declaration
     *
     * @throws UnsupportedDeclarationException if declaration is not supported
     *
     * @return object
     */
    public function instantiate($declaration);
}
