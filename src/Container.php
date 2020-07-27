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
    protected $instances = [];

    /**
     * @var array
     */
    private $resolved = [];

    /**
     * @var array
     */
    private $cached = [];

    public function __construct($definitions = [], $instances = [], $cached = [])
    {
        $this->setDefinitions($definitions);
        $this->setCachedEntries($cached);
        $this->setInstances($instances);
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
                array_keys($this->instances)
            )
        );

        return in_array($id, $keys, true);
    }

    /**
     * Instantiate definition by it's id.
     *
     * @param string $id Definition ID.
     * @return object
     */
    abstract protected function make($id);
}
