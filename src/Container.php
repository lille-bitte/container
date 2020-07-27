<?php

namespace LilleBitte\Container;

use Psr\Container\ContainerInterface;
use LilleBitte\Container\Exception\NotFoundException;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $cached = [];

    /**
     * @var array
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var array
     */
    private $resolvedDefinitions = [];

    public function __construct($definitions = [], $instances = [], $cached = [])
    {
        $this->cached      = $cached;
        $this->definitions = $definitions;
        $this->instances   = $instances;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException(
                sprintf(
                    "Service with id (%s) not found.",
                    $id
                )
            );
        }

        return $this->make($id);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        $keys = array_unique(
            array_merge(
                array_keys($this->cached),
                array_keys($this->definitions),
                array_keys($this->instances)
            )
        );

        return in_array($id, $keys);
    }

    /**
     * Instantiate definition by it's id.
     *
     * @param string $id Definition ID.
     * @return object
     */
    private function make($id)
    {
        if (isset($this->cached[$id])) {
            return $this->cached[$id];
        }

        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        return isset($this->resolvedDefinitions[$id])
            ? $this->resolvedDefinitions[$id]
            : $this->resolveDefinition($id);
    }

    /**
     * Resolve chosen definition by it's ID.
     *
     * @param string $id Definition ID.
     * @return object
     */
    private function resolveDefinition($id)
    {
        $definition = $this->definitions[$id];
        $class      = $definition->getClass();
        $arguments  = $definition->getArguments();

        if ($definition->isAutowired()) {
            return $this->resolveAutowiredClass($class);
        }

        for ($i = 0; $i < sizeof($arguments); $i++) {
            $arguments[$i] = ($arguments[$i] instanceof Reference)
                ? $this->make($arguments[$i]->getId())
                : $arguments[$i];
        }

        return $this->resolvedDefinitions[$id] = (new ReflectionClass($class))->newInstanceArgs($arguments);
    }

    /**
     * Resolve class with autowiring configuration.
     *
     * @param string $class Class name.
     * @return object
     */
    private function resolveAutowiredClass($class)
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->hasMethod('__construct')) {
            return $reflection->newInstanceWithoutConstructor();
        }

        $constructorMethod = $reflection->getConstructor();

        if ($constructorMethod->getNumberOfParameters() === 0) {
            return $reflection->newInstanceArgs([]);
        }

        $constructorMethodParams = [];

        foreach ($constructorMethod->getParameters() as $param) {
            $class = $param->getClass();

            if ($class === null) {
                continue;
            }

            $constructorMethodParams[] = $this->resolveAutowiredClass($class->getName());
        }

        return $reflection->newInstanceArgs($constructorMethodParams);
    }
}
