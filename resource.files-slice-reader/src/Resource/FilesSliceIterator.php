<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class FilesSliceIterator
{
    /**
     * @var DirSliceIterator
     */
    private $dirSliceIterator;

    /**
     * @var \Iterator
     */
    private $fileIterator;

    /**
     * @param DirSliceIterator $iterator
     * @param \Iterator        $fileIterator
     */
    public function __construct(
        DirSliceIterator $iterator,
        \Iterator $fileIterator
    ) {
        $this->dirSliceIterator = $iterator;
        $this->fileIterator = $fileIterator;
    }

    /**
     * @return DirSliceIterator
     */
    public function getDirSliceIterator()
    {
        return $this->dirSliceIterator;
    }

    /**
     * @return \Iterator
     */
    public function getFileIterator()
    {
        return $this->fileIterator;
    }

    /**
     * @param \Iterator $fileIterator
     */
    public function setFileIterator(\Iterator $fileIterator)
    {
        $this->fileIterator = $fileIterator;
    }
}
