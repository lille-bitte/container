<?php

namespace LilleBitte\Container\Loader;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface LoaderStrategyInterface
{
    /**
     * Map configuration file into
     * dependency injection builder.
     *
     * @param string $file Configuration file.
     * @return ContainerBuilderInterface
     */
    public function deserialize(string $file);
}
