<?php

namespace Symsonte\Resource\Cacher;

use Symsonte\Resource\DirResource;
use Symsonte\Resource\DirSliceReader;
use Symsonte\Resource\Storer;
use Symsonte\Resource\UnsupportedResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.cacher.approver']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.cacher.approver']
 * })
 */
class DirModificationTimeApprover implements Approver
{
    /**
     * @var DirSliceReader
     */
    private $reader;

    /**
     * @var Storer
     */
    private $storer;

    /**
     * @var FileModificationTimeApprover
     */
    private $approver;

    /**
     * @param DirSliceReader               $reader
     * @param Storer                       $storer
     * @param FileModificationTimeApprover $approver
     *
     * @ds\arguments({
     *     reader:   '@symsonte.resource.dir_slice_reader',
     *     storer:   '@symsonte.resource.filesystem_storer',
     *     approver: '@symsonte.resource.cacher.file_modification_time_approver'
     * })
     *
     * @di\arguments({
     *     reader:   '@symsonte.resource.dir_slice_reader',
     *     storer:   '@symsonte.resource.filesystem_storer',
     *     approver: '@symsonte.resource.cacher.file_modification_time_approver'
     * })
     */
    public function __construct(
        DirSliceReader $reader,
        Storer $storer,
        FileModificationTimeApprover $approver
    ) {
        $this->reader = $reader;
        $this->storer = $storer;
        $this->approver = $approver;
    }

    /**
     * {@inheritdoc}
     */
    public function add($resource)
    {
        if (!$resource instanceof DirResource) {
            throw new UnsupportedResourceException($resource);
        }

        $iterator = $this->reader->init($resource);
        while ($fileResource = $this->reader->current($iterator)) {
            $this->approver->add($fileResource);

            $this->reader->next($iterator);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function approve($resource)
    {
        if (!$resource instanceof DirResource) {
            throw new UnsupportedResourceException($resource);
        }

        // TODO: Implement a workaround for empty dirs

        $iterator = $this->reader->init($resource);
        while ($fileResource = $this->reader->current($iterator)) {
            if ($this->approver->approve($fileResource) === false) {
                return false;
            }

            $this->reader->next($iterator);
        }

        return true;
    }
}
