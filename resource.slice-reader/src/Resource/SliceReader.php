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
     * @return mixed The iterator.
     *
     * @throws UnsupportedResourceException If given resource is not supported
     * @throws InvalidResourceException     If given resource is invalid
     */
    public function init($resource);

    /**
     * Returns current item in the iteration or false if there are no more
     * items.
     *
     * @param mixed $iterator
     *
     * @return mixed|false The current item in the iteration or false if there are no
     *                     more items.
     *
     * @throws UnsupportedSliceIteratorException If the iterator is not supported.
     */
    public function current($iterator);

    /**
     * Moves internal pointer to the next item in the iteration.
     * If there are no more items, then the method "current" will returns false.
     *
     * @throws UnsupportedSliceIteratorException If the iterator is not supported.
     *
     * @param mixed $iterator
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
