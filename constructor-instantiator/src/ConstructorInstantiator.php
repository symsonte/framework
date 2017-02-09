<?php

namespace Symsonte;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class ConstructorInstantiator
{
    /**
     * Instantiates via constructor given class, passing given arguments if set.
     *
     * @param string $class
     * @param array  $arguments
     *
     * @throws NonexistentClassException if class does not exist
     *
     * @return object
     */
    public function instantiate($class, $arguments = [])
    {
        try {
            $r = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new NonexistentClassException($class);
        }

        if (null === $r->getConstructor()) {
            return $r->newInstance();
        }

        return $r->newInstanceArgs($arguments);
    }
}
