<?php

namespace LilleBitte\Container\Tests\Fixtures;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Bar
{
    /**
     * @var Foo
     */
    private $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function log()
    {
        echo "this is a bar." . PHP_EOL;
    }

    public function getFoo()
    {
        return $this->foo;
    }
}
