<?php

namespace Symsonte\Http\Server\Request\Resolution;

use FastRoute\Dispatcher\GroupCountBased as GroupCountBasedDispatcher;
use FastRoute\DataGenerator\GroupCountBased as GroupCountBasedDataGenerator;
use FastRoute\RouteParser\Std;
use Symsonte\Http\Server\GetRequest;
use Symsonte\Http\Server\PostRequest;
use Symsonte\Http\Server\Request\MethodMatch;
use Symsonte\Http\Server\Request\Resolution;
use Symsonte\Http\Server\Request\UriMatch;
use FastRoute\RouteCollector;
use Zend\Diactoros\Uri;

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
class NikicFastRouteFinder implements Finder
{
    /**
     * @var RouteCollector
     */
    private $collector;

    /**
     */
    function __construct()
    {
        $this->collector = new RouteCollector(
            new Std(),
            new GroupCountBasedDataGenerator()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function first($method, $uri, $version, $headers, $body)
    {
        $dispatcher = new GroupCountBasedDispatcher($this->collector->getData());

        $uri = new Uri($uri);
        
        return $dispatcher->dispatch($method, $uri->getPath());
    }

    /**
     * {@inheritdoc}
     */
    public function merge(Bag $bag)
    {
        foreach ($bag->all() as $resolution) {
            $method = 'GET';
            foreach ($resolution->getMatches() as $match) {
                if ($match instanceof MethodMatch) {
                    $method = $match->getMethod();

                    break;
                }
            }

            $pattern = '';
            foreach ($resolution->getMatches() as $match) {
                if ($match instanceof UriMatch) {
                    $pattern = $match->getPattern();
                }
            }

            $this->collector->addRoute(
                $method,
                $pattern,
                $resolution->getKey()
            );
        }
    }
}