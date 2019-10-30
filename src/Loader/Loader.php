<?php

namespace LilleBitte\Container\Loader;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
final class Loader
{
    /**
     * @var LoaderStrategyInterface
     */
    private $loaderStrategy;

    public function __construct(LoaderStrategyInterface $strategy)
    {
        $this->loaderStrategy = $strategy;
    }

    /**
     * Load dependency configuration from file.
     *
     * @param string $file Configuration file.
     * @return void
     */
    public function load(string $file)
    {
        $this->loaderStrategy->deserialize($file);
    }
}
