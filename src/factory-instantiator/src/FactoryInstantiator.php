<?php

namespace Symsonte;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class FactoryInstantiator
{
    /**
     * Instantiates an object calling given class::method, passing given
     * parameters if set.
     *
     * @param string $class
     * @param string $method
     * @param array  $arguments
     *
     * @throws NonexistentClassException  if class does not exist
     * @throws NonexistentMethodException if method does not exist
     * @throws \ReflectionException
     *
     * @return object
     */
    public function instantiate($class, $method, $arguments = [])
    {
        try {
            $r = new \ReflectionMethod($class, $method);
        } catch (\ReflectionException $e) {
            if (preg_match('/^Class [\w|\s|\\\\]+ does not exist/', $e->getMessage())) {
                throw new NonexistentClassException($class);
            }

            if (preg_match('/^Method [\w|\s|::|()|\\\\]+ does not exist/', $e->getMessage())) {
                throw new NonexistentMethodException($method);
            }

            throw $e;
        }

        return $r->invokeArgs(null, $arguments);
    }
}
