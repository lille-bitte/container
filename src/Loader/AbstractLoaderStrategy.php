<?php

namespace LilleBitte\Container\Loader;

use LilleBitte\Container\ContainerBuilder;

use function realpath;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
abstract class AbstractLoaderStrategy implements LoaderStrategyInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $configDir;

    protected function setContainer(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    protected function getContainer()
    {
        return $this->container;
    }

    protected function setConfigDir(string $dir)
    {
        $this->configDir = $dir;
    }

    protected function getConfigDir()
    {
        return $this->configDir;
    }

    protected function resolveAbsolutePathname()
    {
        $path = realpath($this->getConfigDir());
        return false !== $path ? $path : null;
    }

    abstract public function deserialize(string $file);
}
