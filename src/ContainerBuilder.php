<?php

namespace LilleBitte\Container;

use ReflectionClass;
use LilleBitte\Container\Exception\ContainerException;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ContainerBuilder extends Container implements ContainerBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register($id, $class)
    {
        return $this->setDefinition($id, new Definition($class));
    }

    /**
     * {@inheritdoc}
     */
    public function autowire($id, $class)
    {
        $definition = new Definition($class);
        $definition->setAutowire(true);

        return $this->setDefinition($id, $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefinition($id, Definition $definition)
    {
        return $this->definitions[$id] = $definition;
    }

    /**
     * {@inheritdoc}
     */
    protected function make($id)
    {
        $arguments = $this->definitions[$id]->getArguments();
        $class = $this->definitions[$id]->getClass();

        if (!class_exists($class)) {
            throw new ContainerException(
                sprintf(
                    "Class with name (%s) not exists.",
                    $class
                )
            );
        }

        if ($this->definitions[$id]->isAutowired()) {
            return $this->processAutowiring($class);
        }

        if (!count($arguments)) {
            return new $class;
        }

        foreach ($arguments as $key => $argument) {
            if ($argument instanceof Reference) {
                $arguments[$key] = $this->make($argument->getId());
            }
        }

        $refl = new ReflectionClass($class);
        return $refl->newInstanceArgs($arguments);
    }

    /**
     * Autowire a service.
     *
     * @param string $class Class name.
     * @return object
     */
    private function processAutowiring($class)
    {
        $refl = new ReflectionClass($class);

        if (!$refl->hasMethod('__construct')) {
            return $refl->newInstanceWithoutConstructor();
        }

        $instances = [];
        $refMethod = $refl->getMethod('__construct');

        foreach ($refMethod->getParameters() as $key => $refParam) {
            $class = $refParam->getClass();

            if (!($class instanceof ReflectionClass)) {
                throw new ContainerException(
                    sprintf(
                        "Parameter (\$%s) is not a class.",
                        $refParam->getName()
                    )
                );
            }

            $instances[] = $this->processAutowiring($class->getName());
        }

        return $refl->newInstanceArgs($instances);
    }
}
