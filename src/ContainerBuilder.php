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
}
