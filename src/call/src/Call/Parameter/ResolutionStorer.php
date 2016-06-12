<?php

namespace Symsonte\Call\Parameter;

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
class ResolutionStorer
{
    /**
     * @var Resolution[]
     */
    private $resolutions;

    /**
     * @param Resolution[] $resolutions
     */
    public function __construct($resolutions = [])
    {
        $this->resolutions = $resolutions;
    }

    /**
     * @param string   $class
     * @param string   $method
     * @param string   $value
     *
     * @return string
     */
    public function find(
        string $class,
        string $method,
        string $value
    ) {
        foreach ($this->resolutions as $resolution) {
            if (
                $resolution->getClass() == $class
                && $resolution->getMethod() == $method
                && $resolution->getValue() == $value
            ) {
                return $resolution->getParameter();
            }
        }

        return false;
    }

    /**
     * @param Resolution[] $resolutions
     */
    public function merge(
        $resolutions
    ) {
        $this->resolutions = array_merge(
            $this->resolutions,
            $resolutions
        );
    }
}
