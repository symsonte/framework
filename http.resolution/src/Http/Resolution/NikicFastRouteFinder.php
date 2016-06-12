<?php

namespace Symsonte\Http\Resolution;

use FastRoute\DataGenerator\GroupCountBased as GroupCountBasedDataGenerator;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased as GroupCountBasedDispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Symsonte\Http\MethodMatch;
use Symsonte\Http\PathMatch;
use LogicException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     id: 'symsonte.http.resolution.finder',
 *     private: true
 * })
 */
class NikicFastRouteFinder implements Finder
{
    /**
     * @var RouteCollector
     */
    private $collector;

    public function __construct()
    {
        $this->collector = new RouteCollector(
            new Std(),
            new GroupCountBasedDataGenerator()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function first($method, $path)
    {
        $dispatcher = new GroupCountBasedDispatcher($this->collector->getData());

        $result = $dispatcher->dispatch($method, $path);

        if ($result[0] == Dispatcher::FOUND) {
            // TODO: Return only controller key, as interface dictate
            return [$result[1], $result[2]];
        } elseif ($result[0] == Dispatcher::NOT_FOUND) {
            throw new NotFoundException();
        } elseif ($result[0] == Dispatcher::METHOD_NOT_ALLOWED) {
            throw new LogicException(sprintf(
                'Methods only allowed: %s.',
                implode(', ', $result[1])
            ));
        } else {
            throw new LogicException(print_r($result, true));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $controllers = [];
        foreach ($this->collector->getData() as $groups) {
            foreach ($groups as $method => $routes) {
                $controllers = array_merge(
                    $controllers,
                    $routes
                );
            }
        }

        return $controllers;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(Bag $bag)
    {
        foreach ($bag->all() as $resolution) {
            $methods = ['POST'];
            foreach ($resolution->getMatches() as $match) {
                if ($match instanceof MethodMatch) {
                    $methods = $match->getMethods();

                    break;
                }
            }

            $pattern = '';
            foreach ($resolution->getMatches() as $match) {
                if ($match instanceof PathMatch) {
                    $pattern = $match->getPattern();
                }
            }

            foreach ($methods as $method) {
                $this->collector->addRoute(
                    $method,
                    $pattern,
                    $resolution->getKey()
                );
            }
        }
    }
}
