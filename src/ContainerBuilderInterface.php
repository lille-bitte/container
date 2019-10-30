<?php

namespace LilleBitte\Container;

use Psr\Container\ContainerInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface ContainerBuilderInterface extends ContainerInterface
{
    /**
     * Register service name with an alias.
     *
     * @param string $id Service id.
     * @param string $class Class name.
     * @return Definition
     */
    public function register($id, $class);

    /**
     * Autowire service name aliased by id.
     *
     * @param string $id Service id.
     * @param string $class Class name.
     * @return Definition
     */
    public function autowire($id, $class);

    /**
     * Set definition based on it's id.
     *
     * @param string $id Definition id.
     * @param Definition $definition
     * @return Definition
     */
    public function setDefinition($id, Definition $definition);
}
