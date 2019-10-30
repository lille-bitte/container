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

    /**
     * Set container builder.
     *
     * @param ContainerBuilder $container Container builder
     *                                    instance.
     * @return void
     */
    protected function setContainer(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * Get container builder.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * Set configuration directory.
     *
     * @param string $dir Configuration directory
     * @return void
     */
    protected function setConfigDir(string $dir)
    {
        $this->configDir = $dir;
    }

    /**
     * Get configuration directory.
     *
     * @return string
     */
    protected function getConfigDir()
    {
        return $this->configDir;
    }

    /**
     * Resolve configuration directory to
     * absolute path.
     *
     * @return string|null
     */
    protected function resolveAbsolutePathname()
    {
        $path = realpath($this->getConfigDir());
        return false !== $path ? $path : null;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function deserialize(string $file);
}
