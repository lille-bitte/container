<?php

namespace LilleBitte\Container;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
final class Reference
{
	/**
	 * @var string
	 */
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 * Get reference id.
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}
}
