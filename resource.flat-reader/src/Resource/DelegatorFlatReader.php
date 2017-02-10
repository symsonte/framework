<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorFlatReader implements FlatReader
{
    /**
     * @var FlatReader[]
     */
    protected $flatReaders;

    /**
     * @param FlatReader[] $flatReaders
     */
    public function __construct($flatReaders = [])
    {
        $this->flatReaders = $flatReaders;
    }

    /**
     * {@inheritdoc}
     */
    public function read($resource)
    {
        foreach ($this->flatReaders as $flatReader) {
            try {
                return $flatReader->read($resource);
            } catch (UnsupportedResourceException $e) {
                continue;
            }
        }

        throw new UnsupportedResourceException($resource);
    }
}
