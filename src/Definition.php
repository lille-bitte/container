<?php

namespace LilleBitte\Container;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
final class Definition
{
    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var string
     */
    private $class;

    /**
     * @var boolean
     */
    private $autowired = false;

    public function __construct($class)
    {
        $this->setClass($class);
    }

    /**
     * Add argument into argument stack.
     *
     * @param string $argument Argument value.
     * @return void
     */
    public function addArgument($argument)
    {
        $this->arguments[] = $argument;
    }

    /**
     * Add arguments into argument stack.
     *
     * @param array $arguments List of argument.
     * @return void
     */
    public function addArguments(array $arguments)
    {
        foreach ($arguments as $argument) {
            $this->arguments[] = $argument;
        }
    }

    /**
     * Set list of argument.
     *
     * @param array $arguments List of argument.
     * @return void
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Get list of arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set class name.
     *
     * @param string $class Class name.
     * @return void
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set autowiring option.
     *
     * @param boolean $autowired
     * @return void
     */
    public function setAutowire(bool $autowired)
    {
        $this->autowired = $autowired;
    }

    /**
     * Check if autowiring option is true.
     *
     * @return boolean
     */
    public function isAutowired()
    {
        return $this->autowired;
    }
}
