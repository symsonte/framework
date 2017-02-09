<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface SliceReader
{
    /**
     * Initializes iterator.
     *
     * @param mixed $resource
     *
     * @throws UnsupportedResourceException If given resource is not supported
     * @throws InvalidResourceException     If given resource is invalid
     *
     * @return mixed The iterator.
     */
    public function init($resource);

    /**
     * Returns current item in the iteration or false if there are no more
     * items.
     *
     * @param mixed $iterator
     *
     * @throws UnsupportedSliceIteratorException If the iterator is not supported.
     *
     * @return mixed|false The current item in the iteration or false if there are no
     *                     more items.
     */
    public function current($iterator);

    /**
     * Moves internal pointer to the next item in the iteration.
     * If there are no more items, then the method "current" will returns false.
     *
     *
     * @param mixed $iterator
     *
     * @throws UnsupportedSliceIteratorException If the iterator is not supported.
     */
    public function next($iterator);

    /**
     * Closes iterator.
     *
     * @param mixed $iterator
     *
     * @throws UnsupportedSliceIteratorException If the iterator is not supported.
     */
    public function close($iterator);
}
