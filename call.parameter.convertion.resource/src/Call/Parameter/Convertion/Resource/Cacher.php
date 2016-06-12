<?php

namespace Symsonte\Call\Parameter\Convertion\Resource;

use Symsonte\Resource\Cacher\Approver;
use Symsonte\Resource\Cacher as BaseCacher;
use Symsonte\Resource\Cacher\DelegatorApprover;
use Symsonte\Resource\Storer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class Cacher implements BaseCacher
{
    /**
     * @var Approver
     */
    private $approver;

    /**
     * @var Storer
     */
    private $storer;

    /**
     * @param Approver[] $approvers
     * @param Storer     $storer
     *
     * @ds\arguments({
     *     approvers: '#symsonte.resource.cacher.approver',
     *     storer:    '@symsonte.resource.filesystem_storer'
     * })
     *
     * @di\arguments({
     *     approvers: '#symsonte.resource.cacher.approver',
     *     storer:    '@symsonte.resource.filesystem_storer'
     * })
     */
    public function __construct(
        array $approvers,
        Storer $storer
    ) {
        $this->approver = new DelegatorApprover($approvers);
        $this->storer = $storer;
    }

    /**
     * {@inheritdoc}
     */
    public function store($data, $resource)
    {
        $this->approver->add($resource);
        $this->storer->add($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function approve($resource)
    {
        return $this->approver->approve($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($resource)
    {
        return $this->storer->get($resource);
    }
}
