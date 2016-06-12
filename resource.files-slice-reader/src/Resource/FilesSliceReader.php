<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.slice_reader']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.slice_reader']
 * })
 */
class FilesSliceReader implements SliceReader
{
    /**
     * @var DirSliceReader
     */
    private $dirSliceReader;

    /**
     * @var SliceReader
     */
    private $fileSliceReader;

    /**
     * @param DirSliceReader $dirSliceReader
     * @param SliceReader[]  $fileSliceReaders
     *
     * @ds\arguments({
     *     dirSliceReader:   '@symsonte.resource.dir_slice_reader',
     *     fileSliceReaders: '#symsonte.resource.file_slice_reader'
     * })
     *
     * @di\arguments({
     *     dirSliceReader:   '@symsonte.resource.dir_slice_reader',
     *     fileSliceReaders: '#symsonte.resource.file_slice_reader'
     * })
     */
    public function __construct(
        DirSliceReader $dirSliceReader,
        array $fileSliceReaders
    ) {
        $this->dirSliceReader = $dirSliceReader;
        $this->fileSliceReader = new DelegatorSliceReader($fileSliceReaders);
    }

    /**
     * {@inheritdoc}
     *
     * @return FilesSliceIterator
     */
    public function init($resource)
    {
        if (!$resource instanceof DirResource) {
            throw new UnsupportedResourceException($resource);
        }

        $dirSliceIterator = $this->dirSliceReader->init(
            $resource
        );
        $fileIterator = $this->fileSliceReader->init(
            $this->dirSliceReader->current($dirSliceIterator)
        );

        return new FilesSliceIterator(
            $dirSliceIterator,
            $fileIterator
        );
    }

    /**
     * {@inheritdoc}
     */
    public function current($iterator)
    {
        if (!$iterator instanceof FilesSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $data = $this->fileSliceReader->current(
            $iterator->getFileIterator()
        );

        if ($data === false) {
            $this->dirSliceReader->next(
                $iterator->getDirSliceIterator()
            );
            $fileResource = $this->dirSliceReader->current(
                $iterator->getDirSliceIterator()
            );

            if ($fileResource === false) {
                return false;
            }

            // Assign new file iterator
            $iterator->setFileIterator(
                $this->fileSliceReader->init($fileResource)
            );

            return $this->current($iterator);
        }

        return array_merge(
            $data,
            [
                '_resource' => $this->dirSliceReader->current(
                    $iterator->getDirSliceIterator()
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function next($iterator)
    {
        if (!$iterator instanceof FilesSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }

        $iterator->getFileIterator()->next();

        return $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function close($iterator)
    {
        if (!$iterator instanceof FilesSliceIterator) {
            throw new UnsupportedSliceIteratorException($iterator);
        }
    }
}
