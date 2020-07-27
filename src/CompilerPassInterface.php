<?php

declare(strict_types=1);

namespace LilleBitte\Container;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface CompilerPassInterface
{
    /**
     * Invoke current class to inject dependency
     * into given container.
     *
     * @param ContainerBuilderInterface $container Dependency injection container.
     * @return void
     */
    public function __invoke(ContainerBuilderInterface $container);

    /**
     * Get service tag name.
     *
     * @return string
     */
    public function getTag();

    /**
     * Get associated class name.
     *
     * @return string
     */
    public function getAssociatedClass();

    /**
     * Get serialized service instantiation.
     *
     * @return string
     */
    public function getSerializedValue();
}
