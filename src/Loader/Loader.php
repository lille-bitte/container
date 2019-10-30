<?php

namespace LilleBitte\Container\Loader;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Loader
{
	/**
	 * @var LoaderStrategyInterface
	 */
	private $loaderStrategy;

	public function __construct(LoaderStrategyInterface $strategy)
	{
		$this->loaderStrategy = $strategy;
	}

	public function load(string $file)
	{
		$this->loaderStrategy->deserialize($file);
	}
}
