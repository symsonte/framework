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
class ConvertionStorer
{
    /**
     * @var Convertion[]
     */
    private $convertions;

    /**
     * @param Convertion[] $convertions
     */
    public function __construct($convertions = [])
    {
        $this->convertions = $convertions;
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
        foreach ($this->convertions as $convertion) {
            if (
                $convertion->getClass() == $class
                && $convertion->getMethod() == $method
                && $convertion->getValue() == $value
            ) {
                return $convertion->getParameter();
            }
        }

        return false;
    }

    /**
     * @param Convertion[] $convertions
     */
    public function merge(
        $convertions
    ) {
        $this->convertions = array_merge(
            $this->convertions,
            $convertions
        );
    }
}
