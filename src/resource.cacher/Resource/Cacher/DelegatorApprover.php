<?php

namespace Symsonte\Resource\Cacher;

use Symsonte\Resource\UnsupportedResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorApprover implements Approver
{
    /**
     * @var Approver[]
     */
    protected $approvers;

    /**
     * @param Approver[] $approvers
     */
    public function __construct($approvers)
    {
        $this->approvers = $approvers;
    }

    /**
     * {@inheritdoc}
     */
    public function add($resource)
    {
        foreach ($this->approvers as $approver) {
            try {
                return $approver->add($resource);
            } catch (UnsupportedResourceException $e) {
                continue;
            }
        }

        throw new UnsupportedResourceException($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function approve($resource)
    {
        foreach ($this->approvers as $approver) {
            try {
                return $approver->approve($resource);
            } catch (UnsupportedResourceException $e) {
                continue;
            }
        }

        throw new UnsupportedResourceException($resource);
    }
}
