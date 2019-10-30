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
		return in_array($id, array_keys($this->definitions), true);
	}

	/**
	 * Instantiate definition by it's id.
	 *
	 * @param string $id Definition ID.
	 * @return object
	 */
	abstract protected function make($id);
}
