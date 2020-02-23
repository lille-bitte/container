<?php

namespace LilleBitte\Container;

use Psr\Container\ContainerInterface;
use LilleBitte\Container\Exception\NotFoundException;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
abstract class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected $definitions = [];

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var array
     */
    protected $compilerPasses = [];

    /**
     * @var array
     */
    protected $cached = [];

    /**
     * @var array
     */
    protected $cacheDir;

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

        return in_array($id, $keys, true);
    }

    /**
     * Set container cache directory.
     *
     * @param string $dir Cache directory.
     * @return void
     */
    public function setCacheDir(string $dir)
    {
        $this->cacheDir = $dir;
    }

    /**
     * Get container cache directory.
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * Load container from cache file.
     *
     * @return void
     */
    protected function loadFromCache()
    {
        $file = $this->getCacheDir() . '/container.cache.php';
        $this->cached = file_exists($file) && filesize($file) > 0
            ? require $file
            : [];
    }

    /**
     * Instantiate definition by it's id.
     *
     * @param string $id Definition ID.
     * @return object
     */
    abstract protected function make($id);
}
